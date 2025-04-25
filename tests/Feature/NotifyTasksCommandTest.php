<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifyTasksCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_notify_tasks_command_runs_successfully()
    {
        $this->artisan('notify:tasks')
            ->assertExitCode(0);
    }
}
