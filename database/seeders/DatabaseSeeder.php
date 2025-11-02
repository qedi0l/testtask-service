<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Activity;
use App\Models\Organization;
use App\Models\Phone;
use Database\Factories\PhoneFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 20 activities
        Activity::factory()->count(20)->create()->each(function ($activity){
            $activity->organizations()->saveMany(
                Organization::factory()->count(1)->make()
            );
            // Assign a random number of related activities to each activity (up to 3)
            $relatedCount = fake()->numberBetween(0, 3);
            for ($i = 0; $i < $relatedCount; $i++) {
                $relatedActivity = Activity::inRandomOrder()->first();
                if ($activity->id !== $relatedActivity->id) { // Ensure not to relate an activity to itself
                    $activity->relatedActivities()->attach($relatedActivity);
                }
            }
        });

        Organization::all()->each(function ($organization){
            $organization->phones()->saveMany(Phone::factory()->count(random_int(1,3))->make());
        });

    }
}
