<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Jobs\SendTelegramNotificationJob;

class NotifyTasks extends Command
{
    protected $signature = 'notify:tasks';
    protected $description = 'Send incomplete tasks to subscribed Telegram users';

    public function handle(): int
    {
        $this->info('Starting NotifyTasks command...');

        $response = Http::get('https://jsonplaceholder.typicode.com/todos');

        if (!$response->successful()) {
            $this->error('Failed to fetch tasks');
            return Command::FAILURE;
        }

        $tasks = collect($response->json())
            ->where('completed', false)
            ->where('userId', '<=', 5)
            ->values();

        if ($tasks->isEmpty()) {
            $this->info('No tasks to notify.');
            return Command::SUCCESS;
        }

        $this->info(count($tasks).' tasks found.');

        $users = User::where('subscribed', true)->get();

        $this->info(count($users).' users to notify.');

        foreach ($users as $user) {
            SendTelegramNotificationJob::dispatch($user, $tasks);
        }

        $this->info('Notifications queued successfully.');
        return Command::SUCCESS;
    }
}
