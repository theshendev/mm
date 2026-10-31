<?php

namespace Database\Factories;

use App\Models\Poem;
use App\Support\Persian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Poem>
 */
class PoemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake('fa_IR')->realText(18);
        $stanza = fn (): string => implode("\n", array_map(fn (): string => fake('fa_IR')->realText(40), range(1, 3)));

        return [
            'title' => $title,
            'slug' => Persian::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'body' => $stanza()."\n\n".$stanza(),
            'is_featured' => false,
            'is_published' => true,
            'sort' => fake()->numberBetween(1, 50),
        ];
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    public function hidden(): static
    {
        return $this->state(['is_published' => false]);
    }
}
