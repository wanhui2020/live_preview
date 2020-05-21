<?php

namespace App\Http\Repositories\Criteria;

use App\Repositories\Criteria\Criteria;
use App\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Support\Facades\Auth;

class RelevanceCriteria extends Criteria
{
    private $key = 'MemberUser';


    public function __construct($key = 'MemberUser')
    {
        $this->key = $key;
    }

    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $user = Auth::guard($this->key)->user();
        $model = $model->where('relevance_type', $this->key);
        $model = $model->where('relevance_id', $user->id);

        return $model;
    }
}