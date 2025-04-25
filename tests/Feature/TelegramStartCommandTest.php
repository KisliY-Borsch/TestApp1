<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TelegramStartCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_start_command()
    {
        $response = $this->postJson('/api/telegram/webhook', [
            'message' => [
                'text' => '/start',
                'chat' => ['id' => 123456],
            ]
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'telegram_id' => 123456,
        ]);
    }
}
