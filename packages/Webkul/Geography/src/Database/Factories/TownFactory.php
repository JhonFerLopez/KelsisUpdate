<?php

namespace Webkul\Geography\Database\Factories;

use Webkul\Geography\Models\Town;
use Illuminate\Database\Eloquent\Factories\Factory;

class TownFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Town::class;

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
