<?php

namespace Database\Factories;

use App\Enums\GalleryItemType;
use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => GalleryItemType::Photo,
            'title' => fake('fa_IR')->realText(20),
            'caption' => fake('fa_IR')->realText(60),
            'date_label' => '۱۴۰۲',
            'sort' => fake()->numberBetween(1, 50),
            'is_published' => true,
        ];
    }

    public function video(): static
    {
        return $this->state(['type' => GalleryItemType::Video, 'video_url' => 'https://www.aparat.com/v/example']);
    }

    public function hidden(): static
    {
        return $this->state(['is_published' => false]);
    }
}
