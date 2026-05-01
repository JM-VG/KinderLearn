<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Section;
use App\Models\Module;
use App\Models\Activity;
use App\Models\Announcement;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------------------------
        // 1. Create admin account
        // -----------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@kinderlearn.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password'), 'role' => 'admin', 'avatar' => 'avatar1.png']
        );

        // -----------------------------------------------
        // 2. Create teacher accounts
        // -----------------------------------------------
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@kinderlearn.com'],
            ['name' => 'Ms. Sarah', 'password' => Hash::make('password'), 'role' => 'teacher', 'avatar' => 'avatar2.png']
        );

        $teacher2 = User::firstOrCreate(
            ['email' => 'teacher2@kinderlearn.com'],
            ['name' => 'Mr. Juan', 'password' => Hash::make('password'), 'role' => 'teacher', 'avatar' => 'avatar3.png']
        );

        // -----------------------------------------------
        // 3. Create a class (section)
        // -----------------------------------------------
        $section = Section::firstOrCreate(
            ['join_code' => 'SUN2024'],
            ['name' => 'Sunflower Class', 'teacher_id' => $teacher->id, 'description' => 'Our wonderful kindergarten class!']
        );

        // -----------------------------------------------
        // 4. Create student accounts
        // -----------------------------------------------
        $student = User::firstOrCreate(
            ['email' => 'student@kinderlearn.com'],
            ['name' => 'Maria Clara', 'password' => Hash::make('password'), 'role' => 'student', 'avatar' => 'avatar4.png', 'pin' => '1234', 'section_id' => $section->id]
        );

        User::firstOrCreate(
            ['email' => 'student2@kinderlearn.com'],
            ['name' => 'Jose Rizal Jr.', 'password' => Hash::make('password'), 'role' => 'student', 'avatar' => 'avatar5.png', 'pin' => '5678', 'section_id' => $section->id]
        );

        // -----------------------------------------------
        // 5. Create learning modules
        // -----------------------------------------------
        $modules = [
            [
                'title'      => 'Learn the Alphabet',
                'subject'    => 'alphabet',
                'description'=> 'Learn all 26 letters with fun songs and activities!',
                'icon'       => '🔤',
                'color'      => '#FF6B6B',
                'order'      => 1,
                'teacher_id' => $teacher->id,
            ],
            [
                'title'      => 'Fun with Numbers',
                'subject'    => 'numbers',
                'description'=> 'Count from 1 to 20 and learn basic math!',
                'icon'       => '🔢',
                'color'      => '#4ECDC4',
                'order'      => 2,
                'teacher_id' => $teacher->id,
            ],
            [
                'title'      => 'Colors of the Rainbow',
                'subject'    => 'colors',
                'description'=> 'Learn all the beautiful colors around us!',
                'icon'       => '🌈',
                'color'      => '#45B7D1',
                'order'      => 3,
                'teacher_id' => $teacher->id,
            ],
            [
                'title'      => 'Shapes Everywhere',
                'subject'    => 'shapes',
                'description'=> 'Circles, squares, triangles, and more!',
                'icon'       => '⭐',
                'color'      => '#F7DC6F',
                'order'      => 4,
                'teacher_id' => $teacher->id,
            ],
            [
                'title'      => 'My First Words',
                'subject'    => 'words',
                'description'=> 'Learn simple words like cat, dog, sun, and more!',
                'icon'       => '💬',
                'color'      => '#BB8FCE',
                'order'      => 5,
                'teacher_id' => $teacher->id,
            ],
        ];

        foreach ($modules as $moduleData) {
            $module = Module::firstOrCreate(['subject' => $moduleData['subject']], $moduleData);

            if ($module->activities()->count() > 0) continue;

            Activity::create([
                'module_id'    => $module->id,
                'title'        => 'Watch and Learn',
                'type'         => 'video',
                'content'      => json_encode(['video_url' => 'https://www.youtube.com/embed/hq3yfQnllfQ']),
                'stars_reward' => 2,
                'order'        => 1,
            ]);

            Activity::create([
                'module_id'    => $module->id,
                'title'        => 'Quick Quiz',
                'type'         => 'quiz',
                'content'      => json_encode([
                    'questions' => [
                        [
                            'question' => 'What letter comes first in the alphabet?',
                            'options'  => ['A', 'B', 'C', 'D'],
                            'answer'   => 'A',
                        ],
                        [
                            'question' => 'How many letters are in the alphabet?',
                            'options'  => ['24', '25', '26', '27'],
                            'answer'   => '26',
                        ],
                    ]
                ]),
                'stars_reward' => 3,
                'order'        => 2,
            ]);
        }

        // -----------------------------------------------
        // 6. Create a sample announcement
        // -----------------------------------------------
        Announcement::firstOrCreate(
            ['title' => 'Welcome to KinderLearn!', 'section_id' => $section->id],
            ['teacher_id' => $teacher->id, 'body' => 'Hello Sunflower Class! We are so excited to start learning together. Please complete the Alphabet module first!', 'pinned' => true]
        );

        echo "Seeding complete! Default accounts:\n";
        echo "Admin:   admin@kinderlearn.com / password\n";
        echo "Teacher: teacher@kinderlearn.com / password\n";
        echo "Student: student@kinderlearn.com / password\n";
    }
}
