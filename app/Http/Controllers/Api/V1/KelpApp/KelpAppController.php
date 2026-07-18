<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class KelpAppController extends Controller
{
    public function homeFeed(Request $request): JsonResponse
    {
        $limit = min(max((int) $request->integer('limit', 20), 1), 50);
        $categories = $this->approvedCategories()->get();
        $businesses = $this->approvedBusinesses()
            ->latest()
            ->limit($limit)
            ->get();

        $featuredBusiness = $businesses
            ->sortByDesc(fn (Business $business) => $this->ratingFor($business))
            ->first();

        return response()->json([
            'data' => [
                'currentLocation' => $request->query('location', 'Dar es Salaam, Tanzania'),
                'categories' => $this->categoryPayload($categories, includeAll: true),
                'businesses' => $businesses
                    ->map(fn (Business $business) => $this->businessPayload($business, $request))
                    ->values(),
                'featuredBusinessId' => $featuredBusiness?->id ?? '',
            ],
        ]);
    }

    public function services(): JsonResponse
    {
        return response()->json([
            'data' => $this->categoryPayload($this->approvedCategories()->get()),
        ]);
    }

    public function businesses(Request $request): JsonResponse
    {
        $limit = min(max((int) $request->integer('limit', 20), 1), 50);

        $query = $this->approvedBusinesses();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));

            $query->where(function (Builder $query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhereHas('category', function (Builder $query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        match ($request->query('sort', 'latest')) {
            'rating' => $query->orderByDesc('approved_average_rating'),
            'reviews' => $query->orderByDesc('approved_review_count'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $businesses = $query->limit($limit)->get();

        return response()->json([
            'data' => $businesses
                ->map(fn (Business $business) => $this->businessPayload($business, $request))
                ->values(),
            'meta' => [
                'count' => $businesses->count(),
                'limit' => $limit,
            ],
        ]);
    }

    public function serviceBusinesses(Request $request, Category $category): JsonResponse
    {
        $request->query->set('category_id', $category->id);

        return $this->businesses($request);
    }

    public function businessDetails(Request $request, Business $business): JsonResponse
    {
        abort_unless($business->status === 'approved', 404);

        $business->load([
            'category',
            'images',
            'reviews' => function ($query) {
                $query->where('status', 'approved')->latest()->with('images');
            },
        ]);

        $business->loadCount([
            'reviews as approved_review_count' => function ($query) {
                $query->where('status', 'approved');
            },
        ])->loadAvg([
            'reviews as approved_average_rating' => function ($query) {
                $query->where('status', 'approved');
            },
        ], 'rating');

        return response()->json([
            'data' => $this->businessPayload($business, $request, includeReviews: true),
        ]);
    }

    private function approvedCategories(): Builder
    {
        return Category::query()
            ->withCount([
                'businesses as business_count' => function (Builder $query) {
                    $query->where('status', 'approved');
                },
            ])
            ->orderBy('name');
    }

    private function approvedBusinesses(): Builder
    {
        return Business::query()
            ->where('status', 'approved')
            ->with(['category', 'images'])
            ->withCount([
                'reviews as approved_review_count' => function (Builder $query) {
                    $query->where('status', 'approved');
                },
            ])
            ->withAvg([
                'reviews as approved_average_rating' => function (Builder $query) {
                    $query->where('status', 'approved');
                },
            ], 'rating');
    }

    private function categoryPayload(Collection $categories, bool $includeAll = false): Collection
    {
        $payload = $categories
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'iconKey' => $this->iconKeyFor($category->name),
                'businessCount' => (int) $category->business_count,
            ])
            ->values();

        if (! $includeAll) {
            return $payload;
        }

        return $payload->prepend([
            'id' => 'all',
            'name' => 'All',
            'iconKey' => 'nearby',
            'businessCount' => $payload->sum('businessCount'),
        ]);
    }

    private function businessPayload(
        Business $business,
        Request $request,
        bool $includeReviews = false
    ): array {
        $imageUrl = $this->imageUrl($business->cover_image)
            ?? $this->imageUrl($business->logo)
            ?? $this->imageUrl($business->images->first()?->image_path);

        $payload = [
            'id' => $business->id,
            'name' => $business->name,
            'categoryId' => $business->category_id,
            'categoryName' => $business->category?->name ?? 'Service',
            'rating' => $this->ratingFor($business),
            'reviewCount' => (int) ($business->approved_review_count ?? $business->review_count ?? 0),
            'address' => $business->address,
            'neighborhood' => $business->city,
            'city' => $business->city,
            'country' => $business->country,
            'latitude' => $business->latitude ? (float) $business->latitude : null,
            'longitude' => $business->longitude ? (float) $business->longitude : null,
            'distanceKm' => $this->distanceKm($business, $request),
            'priceRange' => '',
            'isOpenNow' => true,
            'heroTag' => Str::slug($business->name) ?: $business->id,
            'summary' => $business->description ?? '',
            'description' => $business->description,
            'phone' => $business->phone,
            'email' => $business->email,
            'website' => $business->website,
            'imageUrl' => $imageUrl,
            'gallery' => $business->images
                ->map(fn ($image) => $this->imageUrl($image->image_path))
                ->filter()
                ->values(),
            'tags' => collect([
                $business->category?->name,
                $business->city,
                $business->country,
            ])->filter()->values(),
        ];

        if (! $includeReviews) {
            return $payload;
        }

        $payload['reviews'] = $business->reviews
            ->map(fn ($review) => [
                'id' => $review->id,
                'userName' => $review->user_name,
                'rating' => (int) $review->rating,
                'comment' => $review->comment,
                'createdAt' => $review->created_at?->toISOString(),
                'images' => $review->images
                    ->map(fn ($image) => $this->imageUrl($image->image))
                    ->filter()
                    ->values(),
            ])
            ->values();

        return $payload;
    }

    private function ratingFor(Business $business): float
    {
        return round((float) ($business->approved_average_rating ?? $business->average_rating ?? 0), 1);
    }

    private function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/' . $path);
    }

    private function distanceKm(Business $business, Request $request): float
    {
        $hasCoordinates = $request->filled('latitude') && $request->filled('longitude');
        $userLatitude = (float) $request->query('latitude');
        $userLongitude = (float) $request->query('longitude');

        if (! $hasCoordinates || ! $business->latitude || ! $business->longitude) {
            return 0.0;
        }

        $earthRadiusKm = 6371;
        $latDelta = deg2rad((float) $business->latitude - $userLatitude);
        $lngDelta = deg2rad((float) $business->longitude - $userLongitude);

        $angle = sin($latDelta / 2) ** 2
            + cos(deg2rad($userLatitude))
            * cos(deg2rad((float) $business->latitude))
            * sin($lngDelta / 2) ** 2;

        return round($earthRadiusKm * 2 * atan2(sqrt($angle), sqrt(1 - $angle)), 1);
    }

    private function iconKeyFor(string $categoryName): string
    {
        return match (Str::lower($categoryName)) {
            'restaurants' => 'restaurant',
            'cafes' => 'cafe',
            'beauty & wellness' => 'beauty',
            'fitness' => 'fitness',
            'health clinics' => 'health',
            'markets' => 'shopping',
            'electronics' => 'electronics',
            'auto services' => 'auto',
            'hotels' => 'hotel',
            'education' => 'education',
            'entertainment' => 'nightlife',
            default => Str::slug($categoryName),
        };
    }
}
