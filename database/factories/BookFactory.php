<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake('fa_IR')->realText(20);

        return [
            'title' => $title,
            'slug' => fake()->unique()->slug(2),
            'kind' => 'مجموعه شعر',
            'cover_style' => 'sea',
            'meta' => ['ناشر' => fake('fa_IR')->company(), 'سال' => '۱۴۰۲'],
            'description' => fake('fa_IR')->realText(160),
            'sort' => fake()->numberBetween(1, 50),
            'is_published' => true,
        ];
    }

    public function hidden(): static
    {
        return $this->state(['is_published' => false]);
    }
}
