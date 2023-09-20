<?php

namespace Webkul\Preparation\Database\Factories;

use Webkul\Preparation\Models\PreparationTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreparationTranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PreparationTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->word,
            'slug'        => $this->faker->unique()->slug,
            'description' => $this->faker->sentence(),
            'locale'      => 'en',
            'locale_id'   => 1,
        ];
    }
}
