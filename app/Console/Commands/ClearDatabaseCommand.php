<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ClearDatabaseCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear database';

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
        if (!$this->confirm('Are you sure you want to delete user data?[y/N]:')) {
            return;
        }
        $this->work();
    }

    public function work()
    {
        foreach ($this->names() as $name) {
            DB::table($name)->delete();
        }
    }


    protected function names()
    {
        return [
            0  => 'agent_resets',
            1  => 'agent_user_rate',
            2  => 'agent_users',
            3  => 'deal_cashs',
            4  => 'deal_chats',
            5  => 'deal_comments',
            6  => 'deal_conversions',
            7  => 'deal_gifts',
            8  => 'deal_gives',
            9  => 'deal_golds',
            10 => 'deal_likes',
            11 => 'deal_messages',
            12 => 'deal_socials',
            13 => 'deal_talks',
            14 => 'deal_unlocks',
            15 => 'deal_views',
            16 => 'deal_vips',
            17 => 'deal_withdraws',
            18 => 'failed_jobs',
            19 => 'jobs',
            20 => 'member_attentions',
            21 => 'member_blacklists',
            22 => 'member_feedbacks',
            23 => 'member_friends',
            24 => 'member_harass',
            25 => 'member_logins',
            26 => 'member_payees',
            27 => 'member_reports',
            28 => 'member_resources',
            29 => 'member_signins',
            30 => 'member_tags',
            31 => 'member_user_extend',
            32 => 'member_user_parameter',
            33 => 'member_user_rate',
            34 => 'member_user_realname',
            35 => 'member_user_selfie',
            36 => 'member_users',
            37 => 'member_verifications',
            38 => 'member_views',
            39 => 'member_visitors',
            40 => 'member_wallet_cashs',
            41 => 'member_wallet_conversions',
            42 => 'member_wallet_golds',
            43 => 'member_wallet_recharges',
            44 => 'member_wallet_records',
            45 => 'member_wallet_withdraws',
            46 => 'member_wallets',
            48 => 'notifications',
            49 => 'password_resets',
            76 => 'weixin_users',
        ];
    }

    protected function allTableNames()
    {
        $tables = DB::select('show tables');
        $names  = [];
        foreach ($tables as $table) {
            $key     = "Tables_in_"
                .config('database.connections.mysql.database');
            $names[] = $table->{$key};
        }

        \Storage::put('database.txt', var_export($names, 1));

        return $names;
    }
}
