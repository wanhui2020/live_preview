<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\DealFacade;
use App\Facades\ImFacade;
use App\Facades\PlatformFacade;
use App\Jobs\DealJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 视频通话记录
 * */

class DealTalk extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $no = CommonFacade::number('DealTalk', 10000000);
            if (empty($model->no)) {
                $model->no = 'DT-' . $no;
            }
            $model->room_id = $no;

        });


        static::created(function ($model) {
            $dialing = $model->dialing;
            $called = $model->called;
            $resp = ImFacade::sendNotification('talk.begin', [
                'type' => $model->type,
                'room_id' => $model->room_id,
                'form_no' => $dialing->no,
                'form_nickname' => $dialing->nick_name,
                'form_fill_head_pic' => $dialing->fill_head_pic,
                'order_id' => $model->id,
            ], $called->no, $dialing->no, 2);
            if ($resp['status']) {
                $model->status = 8;
                $model->save();
            }
        });

        static::updated(function ($model) {
            //挂断
            if ($model->isDirty('way')) {
//                DealFacade::talkHangup($model->room_id, $model->way);
                //主叫
                $dialing = $model->dialing;
                $called = $model->called;
                //0正常结束 1无应答挂断 2被叫拒绝挂断,3主叫未接通取消 4创建通话失败取消 5通强制挂断   9未结束
                //创建通话房间{"command":"talk.begin","data":{"room_id":123}}
                //接听来电{"command":"talk.answer","data":{"room_id":123}}
                //已接通正常挂断{"command":"talk.hangup","data":{"room_id":123}}
                //通话未接听挂断{"command":"talk.refuse","data":{"room_id":123}}
                //通话取消{"command":"talk.cancel","data":{"room_id":123}}
                //异常挂断{"command":"talk.exception","data":{"room_id":123}}
                //通知被叫方接听{"command":"talk.notification","data":{"room_id":123}}
                //通话扣费通知{"command":"talk.deduction","data":{"usable":3190,"room_id":"10000432","form_id":\"10000019","to_id":"10000017"}}

                $data=[
                    'duration' => $model->duration,
                    'total' => $model->total,
                    'room_id' => $model->room_id,
                    'form_id' => $dialing->no,
                    'to_id' => $called->no,
                    'type' => $model->type,
                    'form_nickname' => $dialing->nick_name,
                    'form_fill_head_pic' => $dialing->fill_head_pic,
                    'order_id' => $model->id,
                ];
                switch ($model->way) {
                    case 0://0正常结束
                        ImFacade::sendNotification('talk.hangup', $data, $dialing->no, $called->no, 2);
                        ImFacade::sendNotification('talk.hangup', $data, $called->no, $dialing->no, 2);
                        break;
                    case 1://1无应答挂断
                        ImFacade::sendNotification('talk.nack', $data, $dialing->no, $called->no, 2);
                        ImFacade::sendNotification('talk.nack', $data, $called->no, $dialing->no, 2);

                        break;
                    case 2://2被叫拒绝挂断
                        ImFacade::sendNotification('talk.refuse', $data, $dialing->no, $called->no, 2);
                        ImFacade::sendNotification('talk.refuse', $data, $called->no, $dialing->no, 2);
                        break;
                    case 3://3主叫未接通取消
                        ImFacade::sendNotification('talk.cancel', $data, $dialing->no, $called->no, 2);
                        ImFacade::sendNotification('talk.cancel', $data, $called->no, $dialing->no, 2);
                        break;
                    case 4://后台挂断
                        ImFacade::sendNotification('talk.finish', $data, $dialing->no, $called->no, 2);
                        ImFacade::sendNotification('talk.finish', $data, $called->no, $dialing->no, 2);
                        break;
                    case 5://异常挂断
                        ImFacade::sendNotification('talk.exception', $data, $dialing->no, $called->no, 2);
                        ImFacade::sendNotification('talk.exception', $data, $called->no, $dialing->no, 2);
                        break;
                }
            }
        });

        static::saving(function ($model) {
            if ($model->isDirty('duration') && $model->duration > 0) {
                $model->total = ceil($model->duration / 60) * $model->price;
                $model->platform_commission = $model->total * $model->platform_rate;
                $model->received = $model->total - $model->platform_commission;
                if ($model->platform_way == 1) {
                    $model->received = $model->received / env('PLATFORM_EXCHANGE_RATE', 100);
                }
            }
        });
        static::saved(function ($model) {

        });


    }

    /**
     * 主叫
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dialing()
    {
        return $this->belongsTo(MemberUser::class, 'dialing_id', 'id');
    }

    /**
     * 被叫
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function called()
    {
        return $this->belongsTo(MemberUser::class, 'called_id', 'id');
    }

}
