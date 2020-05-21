<?php

namespace App\Http\Controllers\System\Deal;

use App\Http\Controllers\Controller;
use App\Models\DealTalk;
use App\Repositories\DealTalkRepository;
use Illuminate\Http\Request;

class TalkController extends Controller
{
    public function __construct(DealTalkRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.deal.talk.index');
    }

    /**
     * @return array
     */
    public function lists()
    {
        try {
            $lists = $this->repository->with(['dialing', 'called'])->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function create(Request $request)
    {
        return view('system.deal.talk.create');

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
            return view('system.deal.talk.edit', compact('data'));
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
            return view('system.deal.talk.detail', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 接听
     */
    public function answer(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->answer($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 挂断
     */
    public function hangup(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->hangup($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 拒绝接听
     */
    public function refuse(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->refuse($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 强制结束
     */
    public function finish(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->finish($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}
