<?php

namespace Webkul\Preparation\Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Webkul\Preparation\Repositories\PreparationRepository;

/*
 * Preparation bulk table seeder.
 *
 * Command: php artisan db:seed --class=Webkul\\Preparation\\Database\\Seeders\\PreparationBulkTableSeeder
 *
 * This seeder has not included anywhere just for development purpose.
 */
class PreparationBulkTableSeeder extends Seeder
{
    private $numberOfParentPreparations = 10;

    private $numberOfChildPreparations = 50;

    public function __construct(
        Faker $faker,
        PreparationRepository $preparationRepository
    ) {
        $this->faker = $faker;
        $this->preparationRepository = $preparationRepository;
    }

    public function run()
    {
        for ($i = 0; $i < $this->numberOfParentPreparations; ++$i) {
            $createdPreparation = $this->preparationRepository->create([
                'slug'        => $this->faker->slug,
                'name'        => $this->faker->firstName,
                'description' => $this->faker->text(),
                'parent_id'   => 1,
                'status'      => 1,
            ]);

            if ($createdPreparation) {
                for ($j = 0; $j < $this->numberOfChildPreparations; ++$j) {

                    $this->preparationRepository->create([
                        'slug'        => $this->faker->slug,
                        'name'        => $this->faker->firstName,
                        'description' => $this->faker->text(),
                        'parent_id'   => $createdPreparation->id,
                        'status'      => 1
                    ]);
                }
            }
        }
    }
}
