<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KelpFavouriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $favourites = Favorite::query()
            ->where('user_id', $request->user()->id)
            ->whereHas('business', fn (Builder $query) => $query->where('status', 'approved'))
            ->with([
                'business' => fn ($query) => $this->businessQuery($query),
            ])
            ->latest()
            ->get();

        return response()->json([
            'data' => $favourites
                ->map(fn (Favorite $favourite) => $this->businessPayload($favourite->business, $request))
                ->filter()
                ->values(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'business_id' => ['required', 'uuid', 'exists:businesses,id'],
        ]);

        $business = $this->businessQuery(Business::query())
            ->where('id', $validated['business_id'])
            ->where('status', 'approved')
            ->firstOrFail();

        Favorite::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'business_id' => $business->id,
        ]);

        return response()->json([
            'message' => 'Favourite saved',
            'data' => $this->businessPayload($business, $request),
        ], 201);
    }

    public function destroy(Request $request, Business $business): JsonResponse
    {
        Favorite::query()
            ->where('user_id', $request->user()->id)
            ->where('business_id', $business->id)
            ->delete();

        return response()->json([
            'message' => 'Favourite removed',
        ]);
    }

    private function businessQuery(Builder $query): Builder
    {
        return $query
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

    private function businessPayload(?Business $business, Request $request): ?array
    {
        if (! $business) {
            return null;
        }

        $imageUrl = $this->imageUrl($business->cover_image)
            ?? $this->imageUrl($business->logo)
            ?? $this->imageUrl($business->images->first()?->image_path);

        return [
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
}
