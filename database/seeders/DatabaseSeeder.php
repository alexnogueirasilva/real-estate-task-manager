<?php

namespace Database\Seeders;

use App\Models\{Building, Comment, Task, User};
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Random\RandomException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        $users     = User::factory(100)->create();
        $buildings = Building::factory(10)->create();

        $buildings->each(function (Building $building) use ($users) {
            $tasks = Task::factory(15)->create([
                'building_id' => $building->id,
                'created_at'  => Carbon::now()->subDays(random_int(0, 30)),
                'updated_at'  => Carbon::now()->subDays(random_int(0, 30)),
            ]);

            $tasks->each(function (Task $task) use ($users) {
                $commentCount = [100, 50, 5][array_rand([100, 50, 5])];

                for ($i = 0; $i < $commentCount; $i++) {
                    Comment::factory()->create([
                        'task_id' => $task->id,
                        'user_id' => $users->random()->id,
                    ]);
                }
            });
        });
    }
}
