<?php

namespace Webkul\Customer\Database\Factories;

use Webkul\Customer\Models\TypeDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TypeDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition(): array
    {
        $name = ucfirst($this->faker->word);
        $prefijo = substr($name, 0, 1);

        return [
            'name'      => $name,
            'prefijo'   => $prefijo,
        ];
    }
}