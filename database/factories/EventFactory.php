<?php

namespace Database\Factories;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'شب شعر '.fake('fa_IR')->realText(20),
            'starts_at' => fake()->dateTimeBetween('+1 week', '+3 months')->setTime(18, 30),
            'venue' => 'خانهٔ فرهنگ',
            'city' => fake('fa_IR')->city(),
            'description' => fake('fa_IR')->realText(120),
            'status' => EventStatus::Scheduled,
            'is_published' => true,
        ];
    }

    public function past(): static
    {
        return $this->state(fn (): array => ['starts_at' => fake()->dateTimeBetween('-2 years', '-1 week')->setTime(18, 0)]);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => EventStatus::Cancelled]);
    }

    public function hidden(): static
    {
        return $this->state(['is_published' => false]);
    }
}
