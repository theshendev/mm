<?php

namespace Database\Factories;

use App\Models\Essay;
use App\Support\Persian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Essay>
 */
class EssayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake('fa_IR')->realText(30);

        return [
            'title' => $title,
            'slug' => Persian::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'excerpt' => fake('fa_IR')->realText(140),
            'body' => '<p>'.fake('fa_IR')->realText(600).'</p><p>'.fake('fa_IR')->realText(600).'</p>',
            'published_on' => fake()->dateTimeBetween('-3 years'),
            'is_published' => true,
        ];
    }

    public function hidden(): static
    {
        return $this->state(['is_published' => false]);
    }
}
