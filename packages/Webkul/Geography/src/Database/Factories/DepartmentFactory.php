<?php

namespace Webkul\Geography\Database\Factories;

use Webkul\Geography\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Department::class;

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
	public function inactive(): DepartmentFactory
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
	public function rtl(): DepartmentFactory
	{
		return $this->state(function (array $attributes) {
			return [
				'direction' => 'rtl',
			];
		});
	}
}
