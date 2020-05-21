<?php

namespace App\Http\Controllers\System\Deal;

use App\Http\Controllers\Controller;
use App\Repositories\DealViewRepository;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function __construct(DealViewRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.deal.view.index');
    }

    /**
     * @return array
     */
    public function lists()
    {
        try {
            $lists = $this->repository->with(['member', 'tomember', 'resource'])->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function create(Request $request)
    {
        return view('system.deal.view.create');

    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->store($data);
            if ($result['status']) {
                return $this->succeed(null, '添加成功');
            } else {
                return $this->failure(1, $result['msg'], $result);
            }
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     */
    public function edit(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.deal.view.edit', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            unset($data['file']);
            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     */
    public function detail(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.deal.view.detail', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}
