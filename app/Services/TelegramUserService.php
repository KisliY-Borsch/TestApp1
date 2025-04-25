<?php

namespace App\Services;

use App\Models\User;

class TelegramUserService
{
    protected TelegramService $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function subscribe(string $chatId, array $message): void
    {
        $user = User::where('telegram_id', $chatId)->first();

        if (!$user) {
            User::create([
                'name' => $message['chat']['first_name'] ?? 'Unnamed',
                'telegram_id' => $chatId,
                'subscribed' => true,
            ]);
        } else {
            $user->update([
                'subscribed' => true,
            ]);
        }

        $this->telegram->sendMessage($chatId, 'Вы успешно подписались на уведомления!');
    }

    public function unsubscribe(string $chatId): void
    {
        $user = User::where('telegram_id', $chatId)->first();

        if ($user) {
            $user->update([
                'subscribed' => false,
            ]);
        }

        $this->telegram->sendMessage($chatId, 'Вы успешно отписались от уведомлений.');
    }
}
