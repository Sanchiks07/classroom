<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Assignment;

class ClassroomAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get an existing teacher
        $teacher = User::where('role', 'teacher')->first();

        if (!$teacher) {
            $this->command->error('No teacher found. Seed users first.');
            return;
        }

        // 2. Create a classroom
        $classroom = Classroom::create([
            'name' => 'Seeded Classroom',
            'teacher_id' => $teacher->id,
            'code' => Str::upper(Str::random(6)),
        ]);

        // 3. Create an assignment
        Assignment::create([
            'classroom_id' => $classroom->id,
            'title' => 'Seeded Assignment',
            'description' => 'This assignment was seeded so you can test comments without rebuilding the universe.',
        ]);
    }
}
