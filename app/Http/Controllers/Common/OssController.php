<?php

namespace App\Http\Controllers\Common;

use App\Facades\OssFacade;
use App\Http\Controllers\Controller;
use App\Service\OssService;
use Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * 图片上传
 * Class OssController
 *
 * @package App\Http\Controllers\Common
 */
class OssController extends Controller
{

    public function putObject(Request $request)
    {
        if ($request->isMethod('POST')) {
            $file = $request->file('file');
            $res  = OssService::putFile($file);

            return $res;
        }

        return $this->validation('请使用POST提交');
    }

    public function pubObjectForLayedit(Request $request)
    {
        $file = $request->file('file');
        $res  = OssService::putFile($file);

        return $res;
    }

    public function putLocalObject(Request $request)
    {
        if ($request->isMethod('POST')) {
            $file     = $request->file('file');
            $content  = file_get_contents($file);
            $fileName = $file->getClientOriginalName();
            $path     = "public/{$fileName}";
            $re       = Storage::disk('local')->put($path, $content, 'public');
            if ($re) {
                return json_encode([
                    'code' => 0,
                    'src'  => "/storage/{$fileName}",
                ]);
            } else {
                return json_encode(['code' => 1]);
            }
        }
    }

    /**
     * oss图片上传
     */
    public function ossPut(Request $request)
    {
        if ($request->isMethod('POST')) {
            $file = $request->file('file');
            $resp = OssFacade::putFile($file);

            return $resp;
        }

        return view('common.oss.put');
    }


    public function ossPutForLayedit(Request $request)
    {
        $resp = OssFacade::putFile(
            $request->file('file')
        );

        return response()->json(
            [
                'code' => Arr::get($resp, 'code'),
                'msg'  => Arr::get($resp, 'code') ? '操作成功' : '操作失败',
                'data' => [
                    'src'   => Arr::get($resp, 'src'),
                    'title' => Arr::get($resp, 'src'),
                ],
            ]
        );
    }

}
