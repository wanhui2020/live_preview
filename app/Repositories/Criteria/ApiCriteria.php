<?php

namespace App\Repositories\Criteria;

use App\Repositories\Criteria\Criteria;
use App\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Support\Facades\Auth;

class ApiCriteria extends Criteria
{


    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $model = $model->where('customer_id', $user->id);
        } else {
            $model = $model->where('customer_id', 0);
        }
        return $model;
    }
}