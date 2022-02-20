<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{

    /**
     * @var string
    **/
    protected $model = Album::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->words(10, true)
        ];
    }
}
