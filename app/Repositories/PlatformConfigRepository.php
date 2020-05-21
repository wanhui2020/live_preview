<?php

namespace App\Http\Repositories;

use App\Models\PlatformConfig;
use App\Repositories\BaseRepository;
use App\Models\PlatformRate;

class PlatformConfigRepository extends BaseRepository
{
    public function model()
    {
        return PlatformConfig::class;
    }

    public function update($data, $attribute = "id")
    {
        return parent::update($data, $attribute);
    }
}
