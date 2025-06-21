<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Groups;
use App\Models\Notes;
use App\Models\Tasks;

class InoteSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'user_' . Str::random(8),
            'email' => 'user_' . Str::random(8) . '@test.com',
            'password' => bcrypt('TestPass123!'),
        ]);

        $group = Groups::create([
            'name' => 'group_' . Str::random(8),
            'entry_code' => Str::random(6),
            'leader' => $user->id,
        ]);

        if (method_exists($group, 'members')) {
            $group->members()->attach($user->id);
        }

        Notes::create([
            'title' => 'Test Note ' . Str::random(5),
            'note' => 'This is a test note.',
            'category' => $group->name,
            'by' => $user->id,
        ]);

        Tasks::create([
            'by' => $user->name,
            'title' => 'Test Task ' . Str::random(5),
            'category' => $group->name,
            'task_items' => json_encode([['item' => 'Do something']]),
        ]);
    }
}
