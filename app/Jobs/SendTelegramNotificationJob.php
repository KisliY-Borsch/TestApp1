<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendTelegramNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $tasks;

    public function __construct($user, $tasks)
    {
        $this->user = $user;
        $this->tasks = $tasks;
    }

    public function handle(): void
    {
        $token = config('services.telegram.bot_token');

        $text = "Ваши незавершённые задачи:\n\n";
        foreach ($this->tasks as $task) {
            $text .= "• {$task['title']}\n";
        }

        Http::asForm()->post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $this->user->telegram_id,
            'text' => $text,
        ]);
    }
}
