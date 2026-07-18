<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessImage;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BusinessDemoSeeder extends Seeder
{
    /**
     * Seed approved sample businesses with web-hosted images.
     */
    public function run(): void
    {
        $categories = collect([
            'Restaurants',
            'Cafes',
            'Beauty & Wellness',
            'Fitness',
            'Health Clinics',
            'Markets',
            'Electronics',
            'Auto Services',
            'Hotels',
            'Education',
            'Entertainment',
        ])->mapWithKeys(function (string $name) {
            return [$name => Category::updateOrCreate(['name' => $name])];
        });

        $businesses = [
            [
                'category' => 'Restaurants',
                'name' => 'Kivukoni Grill House',
                'description' => 'Coastal grill serving fresh seafood, mishkaki, and late-evening plates for groups near the ferry.',
                'phone' => '+255 712 100 101',
                'email' => 'hello@kivukonigrill.test',
                'website' => 'https://example.com/kivukoni-grill',
                'address' => 'Kivukoni Front, Ferry Road',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.8235000,
                'longitude' => 39.2948000,
                'image' => 'https://images.unsplash.com/photo-1758537697448-dbfc1cb83e49?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Cafes',
                'name' => 'Mikocheni Coffee Bar',
                'description' => 'Neighborhood cafe with Tanzanian coffee, pastries, quiet work tables, and quick breakfast service.',
                'phone' => '+255 712 100 102',
                'email' => 'hello@mikochenicoffee.test',
                'website' => 'https://example.com/mikocheni-coffee',
                'address' => 'Rose Garden Road, Mikocheni',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.7627000,
                'longitude' => 39.2442000,
                'image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Beauty & Wellness',
                'name' => 'Oyster Bay Wellness Spa',
                'description' => 'Calm massage and skincare studio with body treatments, aromatherapy, and weekend wellness packages.',
                'phone' => '+255 712 100 103',
                'email' => 'care@oysterbayspa.test',
                'website' => 'https://example.com/oyster-bay-spa',
                'address' => 'Toure Drive, Oyster Bay',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.7707000,
                'longitude' => 39.2853000,
                'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Beauty & Wellness',
                'name' => 'Kinondoni Barber Studio',
                'description' => 'Modern barber studio for fades, beard trims, kids cuts, and quick grooming appointments.',
                'phone' => '+255 712 100 104',
                'email' => 'book@kinondonibarber.test',
                'website' => 'https://example.com/kinondoni-barber',
                'address' => 'Mkwajuni Street, Kinondoni',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.7819000,
                'longitude' => 39.2556000,
                'image' => 'https://images.unsplash.com/photo-1759134248487-e8baaf31e33e?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Markets',
                'name' => 'Kariakoo Fresh Market',
                'description' => 'Fresh produce, pantry staples, juices, and local snacks with same-day packing for nearby offices.',
                'phone' => '+255 712 100 105',
                'email' => 'orders@kariakoofresh.test',
                'website' => 'https://example.com/kariakoo-fresh',
                'address' => 'Msimbazi Street, Kariakoo',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.8218000,
                'longitude' => 39.2756000,
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Fitness',
                'name' => 'Masaki Fitness Lab',
                'description' => 'Strength training gym with personal coaching, early morning classes, and flexible monthly passes.',
                'phone' => '+255 712 100 106',
                'email' => 'train@masakifitness.test',
                'website' => 'https://example.com/masaki-fitness',
                'address' => 'Haile Selassie Road, Masaki',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.7543000,
                'longitude' => 39.2779000,
                'image' => 'https://images.unsplash.com/photo-1570829460005-c840387bb1ca?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Health Clinics',
                'name' => 'Upanga Dental Care',
                'description' => 'Family dental clinic offering checkups, cleaning, whitening, and emergency tooth-care support.',
                'phone' => '+255 712 100 107',
                'email' => 'frontdesk@upangadental.test',
                'website' => 'https://example.com/upanga-dental',
                'address' => 'Ali Hassan Mwinyi Road, Upanga',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.8028000,
                'longitude' => 39.2818000,
                'image' => 'https://images.unsplash.com/photo-1616391182219-e080b4d1043a?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Electronics',
                'name' => 'Posta Tech Repairs',
                'description' => 'Phone, laptop, and tablet repair counter for screen replacements, diagnostics, and accessories.',
                'phone' => '+255 712 100 108',
                'email' => 'support@postatech.test',
                'website' => 'https://example.com/posta-tech',
                'address' => 'Samora Avenue, Posta',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.8162000,
                'longitude' => 39.2871000,
                'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Auto Services',
                'name' => 'Mwenge Auto Garage',
                'description' => 'Reliable garage for oil service, brake checks, engine diagnostics, and pre-trip inspections.',
                'phone' => '+255 712 100 109',
                'email' => 'service@mwengeauto.test',
                'website' => 'https://example.com/mwenge-auto',
                'address' => 'Shekilango Road, Mwenge',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.7713000,
                'longitude' => 39.2222000,
                'image' => 'https://images.unsplash.com/photo-1487754180451-c456f719a1fc?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Hotels',
                'name' => 'Slipway Boutique Hotel',
                'description' => 'Boutique stay with breezy rooms, concierge help, breakfast service, and quick access to the peninsula.',
                'phone' => '+255 712 100 110',
                'email' => 'stay@slipwayboutique.test',
                'website' => 'https://example.com/slipway-boutique',
                'address' => 'Yacht Club Road, Msasani Peninsula',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.7497000,
                'longitude' => 39.2756000,
                'image' => 'https://images.unsplash.com/photo-1621293954908-907159247fc8?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Education',
                'name' => 'Mbezi Learning Hub',
                'description' => 'After-school tutoring, exam prep, computer basics, and small group lessons for young learners.',
                'phone' => '+255 712 100 111',
                'email' => 'learn@mbezihub.test',
                'website' => 'https://example.com/mbezi-learning',
                'address' => 'Mbezi Beach Road',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.7116000,
                'longitude' => 39.2185000,
                'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category' => 'Entertainment',
                'name' => 'Coco Beach Events',
                'description' => 'Event production crew for beach shows, private parties, sound systems, lighting, and stage setup.',
                'phone' => '+255 712 100 112',
                'email' => 'events@cocobeachevents.test',
                'website' => 'https://example.com/coco-beach-events',
                'address' => 'Coco Beach, Oyster Bay',
                'city' => 'Dar es Salaam',
                'country' => 'Tanzania',
                'latitude' => -6.7681000,
                'longitude' => 39.2899000,
                'image' => 'https://images.unsplash.com/photo-1745792820182-bba49ed8a0a4?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        foreach ($businesses as $index => $data) {
            $email = $data['email'];

            $owner = User::updateOrCreate([
                'email' => $email,
            ], [
                'name' => $data['name'] . ' Owner',
                'phone_number' => $data['phone'],
                'password' => Hash::make('password'),
                'role' => 'business_owner',
            ]);

            $business = Business::updateOrCreate([
                'name' => $data['name'],
            ], [
                'user_id' => $owner->id,
                'category_id' => $categories[$data['category']]->id,
                'description' => $data['description'],
                'phone' => $data['phone'],
                'email' => $email,
                'website' => $data['website'],
                'address' => $data['address'],
                'city' => $data['city'],
                'country' => $data['country'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'logo' => $data['image'],
                'cover_image' => $data['image'],
                'status' => 'approved',
            ]);

            $business->images()->delete();

            foreach ([$data['image'], $this->galleryImageFor($index)] as $imagePath) {
                BusinessImage::create([
                    'id' => (string) Str::uuid(),
                    'business_id' => $business->id,
                    'image_path' => $imagePath,
                ]);
            }

            $this->seedReviews($business, $index);
        }
    }

    private function seedReviews(Business $business, int $index): void
    {
        $reviews = [
            [
                'user_name' => 'Amina Juma',
                'rating' => 5,
                'comment' => 'Clean space, friendly service, and the staff handled everything quickly.',
            ],
            [
                'user_name' => 'Brian Msangi',
                'rating' => $index % 3 === 0 ? 4 : 5,
                'comment' => 'Easy to find, fair pricing, and exactly the kind of local spot I would recommend.',
            ],
            [
                'user_name' => 'Neema Charles',
                'rating' => $index % 4 === 0 ? 4 : 5,
                'comment' => 'The experience felt personal and polished. I would go back without hesitation.',
            ],
        ];

        foreach ($reviews as $review) {
            Review::updateOrCreate([
                'business_id' => $business->id,
                'user_name' => $review['user_name'],
            ], [
                'rating' => $review['rating'],
                'comment' => $review['comment'],
                'status' => 'approved',
            ]);
        }

        $business->forceFill([
            'average_rating' => round((float) $business->reviews()->avg('rating'), 1),
            'review_count' => $business->reviews()->count(),
        ])->save();
    }

    private function galleryImageFor(int $index): string
    {
        $galleryImages = [
            'https://images.unsplash.com/photo-1753727471014-efe38840c7c7?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1545612036-2872840642dc?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1764448726322-2abd2bdd6c68?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1770321119305-f191c09c5801?auto=format&fit=crop&w=1200&q=80',
        ];

        return $galleryImages[$index % count($galleryImages)];
    }
}
