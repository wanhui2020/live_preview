<?php

namespace App\Repositories;

use App\Models\MemberLogin;
use App\Models\MemberUserExtend;
use App\Models\MemberUserRate;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * 会员扩展
 * Class MemberUserExtendRepository
 * @package App\Repositories
 */
class MemberUserExtendRepository extends BaseRepository
{
    public function model()
    {
        return MemberUserExtend::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->whereHas('member', function ($query) {
                        $query->where('no', 'like', '%' . request('key') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('key') . '%')
                            ->orWhere('mobile', 'like', '%' . request('key') . '%');
                    });
                });
            }
            if (request('status') != null) {
                $query->orWhere('weixin_verify', 'like', '%' . request('status') . '%')
                    ->orWhere('qq_verify', 'like', '%' . request('status') . '%');
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
            $parameter = MemberUserExtend::firstOrNew(['id' => $data['id']]);
            $parameter->fill($data);
            $parameter->save();
            DB::commit();
            return $this->succeed($parameter);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

}

