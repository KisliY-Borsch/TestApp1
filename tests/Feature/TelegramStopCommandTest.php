<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class TelegramStopCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_stop_command()
    {
        User::factory()->create([
            'telegram_id' => 123456,
        ]);

        $response = $this->postJson('/api/telegram/webhook', [
            'message' => [
                'text' => '/stop',
                'chat' => ['id' => 123456],
            ]
        ]);

        $response->assertOk();

        $this->assertDatabaseMissing('users', [
            'chat' => 123456,
        ]);
    }
}
