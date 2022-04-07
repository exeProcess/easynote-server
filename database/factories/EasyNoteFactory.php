<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EasyNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'author' => $this->faker->name,
            'title' => $this->faker->word,
            'description' =>$this->faker->sentence,
            'body' => $this->faker->paragraph,
            'created_by' => rand(1, 3)
        ];
    }
}
