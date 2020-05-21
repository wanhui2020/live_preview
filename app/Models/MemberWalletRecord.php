<?php

namespace App\Models;

use App\Facades\CommonFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员金币明细
 * */

class MemberWalletRecord extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        Relation::morphMap([
            'MemberWalletCash' => MemberWalletCash::class,
            'MemberWalletGold' => MemberWalletGold::class,
        ]);
        static::creating(function ($model) {

        });
        static::created(function ($model) {


        });
    }

    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    /**
     * 所属业务
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relevance()
    {
        return $this->morphTo('relevance', 'relevance_type', 'relevance_id');
    }

}
