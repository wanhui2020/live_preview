<?php

namespace App\Jobs;

use App\Models\MemberUser;
use App\Repositories\MemberUserRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAutoMessageJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $memberId;

    /**
     * Create a new job instance.
     *
     * @param $memberId
     */
    public function __construct($memberId)
    {
        $this->memberId = $memberId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = MemberUser::find($this->memberId);
        if (!$user) {
            return;
        }

        if (
            $user->last_auto_message_at
            && Carbon::parse($user->last_auto_message_at)->isToday()
        ) {
            return;
        }

        app(MemberUserRepository::class)->sendMessage($user);
    }
}
