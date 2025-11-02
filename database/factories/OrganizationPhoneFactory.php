<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\OrganizationPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationPhoneFactory extends Factory
{
    protected $model = OrganizationPhone::class;

    public function definition(): array
    {
        return [
            'phone' => $this->faker->phoneNumber(),

            'organization_id' => Organization::factory(),
        ];
    }
}
