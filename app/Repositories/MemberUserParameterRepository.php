<?php

namespace App\Repositories;

use App\Models\MemberLogin;
use App\Models\MemberUserParameter;
use App\Models\MemberUserRate;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * 会员参数
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class MemberUserParameterRepository extends BaseRepository
{
    public function model()
    {
        return MemberUserParameter::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->whereHas('member', function ($query) {
                        $query->where('no', 'like', '%' . request('key') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('key') . '%');
                    });
                });
            }
            if (request('status') != null) {
                $query->where('status', request('status'));
            }
        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        if (request('size') != null) {
            $perPage = request('size');
            return $this->paginate($perPage);
        }
        return $this->paginate();
    }


    public function update($data, $attribute = "id")
    {
        DB::beginTransaction();
        try {
            $parameter = MemberUserParameter::firstOrNew(['member_id' => $data['member_id']]);
            if (isset($data['is_disturb'])) {
                $parameter->is_disturb = $data['is_disturb'];
            }
            if (isset($data['is_location'])) {
                $parameter->is_location = $data['is_location'];
            }
            if (isset($data['is_stranger'])) {
                $parameter->is_stranger = $data['is_stranger'];
            }
            if (isset($data['is_text'])) {
                $parameter->is_text = $data['is_text'];
            }
            if (isset($data['is_voice'])) {
                $parameter->is_voice = $data['is_voice'];
            }
            if (isset($data['is_video'])) {
                $parameter->is_video = $data['is_video'];
            }
            if (isset($data['greeting'])) {
                $parameter->greeting = $data['greeting'];
            }
            if (isset($data['wechat_view'])) {
                $parameter->wechat_view = $data['wechat_view'];
            }
            if (isset($data['greeting'])) {
                $parameter->greeting = $data['greeting'];
            }
            if (isset($data['is_answer_host_phonep'])) {
                $parameter->is_answer_host_phonep = $data['is_answer_host_phonep'];
            }
            if (isset($data['is_screencap'])) {
                $parameter->is_screencap = $data['is_screencap'];
            }
            $parameter->save();
            DB::commit();
            return $this->succeed($parameter);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

}

