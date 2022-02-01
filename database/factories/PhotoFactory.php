<?php

namespace Database\Factories;

use App\Models\Photo;
use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Http\File;
use Str, Storage;

class PhotoFactory extends Factory
{

    /**
     * @var string
     */
    protected  $model = Photo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $image = $this->faker->image();
        $imageFile = new File($image);

        return [
            //
            'album_id' => Album::factory(),
            'title' => $this->faker->sentence,
            'thumbnail_path' => $path= 'storage/'.Storage::disk('public')->putFile('photos', $imageFile),
            'thumbnail_url' => config('app.url').'/'.Str::after($path, 'public/'),

        ];
    }
}
