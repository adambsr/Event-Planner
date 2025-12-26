<?php

namespace Database\Factories;

use App\Models\AAB_Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AAB_Category>
 */
class AAB_CategoryFactory extends Factory
{
    protected $model = AAB_Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Technology',
            'Business',
            'Arts & Culture',
            'Sports',
            'Education',
            'Health & Wellness',
            'Music',
            'Food & Drink',
            'Travel',
            'Science',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($categories),
        ];
    }
}
