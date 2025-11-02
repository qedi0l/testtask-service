<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Organization;
use App\Models\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'building_id' => Building::factory(),
            'name' => $this->faker->text(),
        ];
    }
}
