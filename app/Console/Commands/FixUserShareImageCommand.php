<?php

namespace App\Console\Commands;

use App\Jobs\GenerateShareImageJob;
use App\Models\MemberUser;
use Illuminate\Console\Command;

class FixUserShareImageCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:share-image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        MemberUser::select('id')
            ->where('share_image', '')
            ->chunk(500, function ($chunks) {
                foreach ($chunks as $user) {
                    dispatch(new GenerateShareImageJob($user->id));
                }
            });
    }
}
