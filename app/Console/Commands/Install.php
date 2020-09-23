<?php

namespace App\Console\Commands;

use App\User;
use App\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('key:generate');
        Artisan::call('migrate');

        $user = User::query()->firstOrCreate([
            'name' => 'hi',
            'email' => 'hi@example.com',
            'password' => bcrypt('secret'),
        ]);
        $task1 = Task::query()->firstOrCreate([
            'name' => 'feeding cats',
            'user_id' => $user->getKey(),
            'description' => 'The cats get hungry every day.',
        ]);

        tempTags($task1)->tagIt('complete', Carbon::tomorrow()->startOfDay());

        Task::query()->firstOrCreate([
            'name' => 'reading book',
            'user_id' => $user->getKey(),
            'description' => 'increase your knowledge.',
        ]);

        Task::query()->firstOrCreate([
            'name' => 'exercise out doors',
            'user_id' => $user->getKey(),
            'description' => 'Take care of health.',
        ]);

        $user = User::query()->firstOrCreate([
            'name' => 'bye',
            'email' => 'bye@example.com',
            'password' => bcrypt('secret'),
        ]);
        $task1 = Task::query()->firstOrCreate([
            'name' => 'feeding dogs',
            'user_id' => $user->getKey(),
            'description' => 'The dogs get hungry every day.',
        ]);

        tempTags($task1)->tagIt('complete', Carbon::tomorrow()->startOfDay());

        Task::query()->firstOrCreate([
            'name' => 'reading newslatter',
            'user_id' => $user->getKey(),
            'description' => 'increase your knowledge.',
        ]);


        Task::query()->firstOrCreate([
            'name' => 'exercise in doors',
            'user_id' => $user->getKey(),
            'description' => 'Take care of health.',
        ]);

        $this->info('We migrated and seeded the db with some users and tasks.  \(^_^)/');
        $this->info('You can now login     email: bye@example.com    password: secret');
        $this->info('You can now login     email: hi@example.com     password: secret');

        return 1;
    }
}
