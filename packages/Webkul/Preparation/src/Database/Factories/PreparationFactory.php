<?php

namespace Webkul\Preparation\Database\Factories;

use Webkul\Preparation\Models\Preparation;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreparationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Preparation::class;

    /**
     * @var string[]
     */
    protected $states = [
        'inactive',
        'rtl',
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'status' => 1,
            'position' => $this->faker->randomDigit(),
            'parent_id' => 1,
        ];
    }

    /**
     *
     */
    public function inactive(): PreparationFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 0,
            ];
        });
    }

    /**
     * Handle rtl state
     */
    public function rtl(): PreparationFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'direction' => 'rtl',
            ];
        });
    }
}
