<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function MongoDB\BSON\toJSON;

class EnvController extends Controller
{
    /**
     * 页面视图
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';
            $config = collect(file($envPath, FILE_IGNORE_NEW_LINES));
            $config=$config->toArray();
            $data=[];
            foreach ($config as $val){
                if(strpos($val,'=')!==false) {
                    $val = explode('=', $val);
                    $data[$val[0]] = $val[1];
                }
            }
            $config = json_encode($data);
            return view('system.platform.env.edit', compact('config'));
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     * 修改
     * @param Request $request
     * @return array|string
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();

            $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';
            $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
            $contentArray->transform(function ($item) use ($data) {
                foreach ($data as $key => $value) {
                    if (strstr($item, $key)) {
                        return $key . '=' . $value;
                    }
                }
                return $item;
            });

            $content = implode($contentArray->toArray(), "\n");
            \File::put($envPath, $content);
            return $this->succeed(0);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


}
