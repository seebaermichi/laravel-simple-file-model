<?php

namespace MichaelBecker\SimpleFile\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MichaelBecker\SimpleFile\Models\File;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        $userId = config('files.user_has_uuid') ? Str::uuid()->toString() : $this->faker->randomNumber();

        return [
            'disk' => 'public',
            'name' => $this->faker->name().'.'.$this->faker->fileExtension(),
            'path' => $this->faker->name(),
            'uploaded_by' => $userId,
            'fileable_id' => $this->faker->uuid(),
            'fileable_type' => File::class,
        ];
    }

    /**
     * Define the `fileable` polymorphic relationship.
     * This state allows setting an arbitrary model as the `fileable`.
     *
     * @param  \Illuminate\Database\Eloquent\Model|object  $fileable
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function fileable($fileable)
    {
        return $this->state(function () use ($fileable) {
            return [
                'fileable_id' => $fileable->id,
                'fileable_type' => get_class($fileable),
            ];
        });
    }
}
