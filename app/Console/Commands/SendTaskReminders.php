<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskReminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send email reminders for tasks due tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Load users in chunks (100 per chunk) with their tasks due tomorrow
        User::with(['tasks' => function($query) use ($tomorrow) {
            $query->where('due_date', $tomorrow);
        }])->chunk(100, function ($users) {

            foreach ($users as $user) {
                Log::info(['asad21' => $user->tasks]);
                foreach ($user->tasks as $task) {
                    // Dispatch each task as a queued job
                    dispatch(new SendTaskReminder($task));
                }
            }

        });

        $this->info('Task reminders dispatched successfully.');
    }
}
