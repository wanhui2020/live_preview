<?php

namespace App\Console\Commands;

use App\Models\MemberUser;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use Illuminate\Console\Command;

class ClearTestDataCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:test-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear test data.';

    /**
     * @var array
     */
    protected $memberIds;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $phones = collect(config('developer'))->pluck('phones')->flatten(1)
            ->toArray();

        $this->memberIds = MemberUser::select('id')
            ->whereIn('mobile', $phones)
            ->pluck('id')
            ->toArray();

        if (!$this->memberIds) {
            return;
        }

        $this->walletGold()->walletCash();
    }

    protected function walletCash()
    {
        MemberWalletCash::whereIn('member_id', $this->memberIds)
            ->update([
                'balance' => 0,
                'usable'  => 0,
                'lock'    => 0,
            ]);

        return $this;
    }

    protected function walletGold()
    {
        MemberWalletGold::whereIn('member_id', $this->memberIds)
            ->update([
                'balance' => 0,
                'usable'  => 0,
                'lock'    => 0,
            ]);

        return $this;
    }

}
