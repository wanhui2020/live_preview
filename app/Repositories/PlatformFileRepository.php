<?php
namespace App\Http\Repositories;

use App\Repositories\BaseRepository;
use App\Models\PlatformFile;
class PlatformFileRepository extends BaseRepository
{
    public function model()
    {
        return PlatformFile::class;
    }
    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('no')) {
                $query->where(function ($query) {
                    $query->where('no', 'like', '%' . request('no') . '%');
                });
            }
            if (request('multiple')!=null) {
                $query->where('multiple', 'like', '%' . request('multiple') . '%');
            }
            if (request('status')!=null) {
                $query->where('status', request('status'));
            }
        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        return parent::paginate();

    }

    public function store(array $data)
    {
        return parent::store($data);
    }

    public function update($data, $attribute = "id")
    {
        return parent::update($data, $attribute);
    }

    public function destroy(array $ids)
    {
        return parent::destroy($ids);
    }
}
