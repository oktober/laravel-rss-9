<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'entry_url' => $this->faker->unique()->url,
            'entry_title' => $this->faker->sentence(),
            'entry_teaser' => $this->faker->paragraph(2),
            'entry_content' => $this->faker->realText(),
            'entry_last_updated' => now(),
        ];
    }
}
