<?php

namespace Database\Factories;

use App\Models\AAB_Event;
use App\Models\AAB_Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AAB_Event>
 */
class AAB_EventFactory extends Factory
{
    protected $model = AAB_Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+3 months');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 3) . ' days');
        $isFree = $this->faker->boolean(30); // 30% chance of being free

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'place' => $this->faker->address,
            'price' => $isFree ? 0 : $this->faker->randomFloat(2, 10, 500),
            'category_id' => AAB_Category::factory(),
            'capacity' => $this->faker->numberBetween(20, 1000),
            'created_by' => User::factory(),
            'is_free' => $isFree,
            'status' => $this->faker->randomElement(['active', 'archived']),
        ];
    }

    /**
     * Indicate that the event is free.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_free' => true,
            'price' => 0,
        ]);
    }

    /**
     * Indicate that the event is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_free' => false,
            'price' => $this->faker->randomFloat(2, 10, 500),
        ]);
    }

    /**
     * Indicate that the event is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the event is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }
}
