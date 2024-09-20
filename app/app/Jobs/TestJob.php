<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TestJob implements ShouldQueue
{
    use Queueable;

    private string $time;

    private User $user;

    public function __construct(string $time, User $user)
    {
        $this->time = $time;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        print_r($this->time);
        print_r(PHP_EOL);
        print_r($this->user->id);
        print_r(PHP_EOL);
    }
}
