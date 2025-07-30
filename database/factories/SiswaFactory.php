<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->unique()->randomDigit(),
            'nama' => $this->faker->name(),
            'nisn' => $this->faker->unique()->numerify('#########'),
            'ttl' => $this->faker->numerify('Surabaya ##-##-####'),
            'jk' => $this->faker->words(5),
            'agama' => $this->faker->words(5),
            'alamat' => $this->faker->words(7),
            'no_telp' => $this->faker->unique()->numerify('#########'),
            'jurusan_id' => mt_rand(1, 2),
            'n_ayah' => $this->faker->name(),
            'n_ibu' => $this->faker->name(),
            'alamat_ortu' => $this->faker->words(6),
            'no_telp_rumah' => $this->faker->numerify('#########'),
        ];
    }
}