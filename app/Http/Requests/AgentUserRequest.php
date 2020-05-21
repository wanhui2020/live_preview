<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AgentUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        switch ($this->getA($request)) {
            case 'store':
                return [
                    'name' => 'nullable',
                    'email' => 'required|email|unique:agent_users',
                    'phone' => 'nullable|regex:/^1\d{10}$/',
                    'password' => 'required|min:6|max:12|regex:/^[\S]{6,12}$/',
                ];
            case 'update':
                return [
                    'name' => 'nullable',
                    'email' => 'required|email|unique:agent_users,email,'.$request->id,
                    'phone' => 'nullable|regex:/^1\d{10}$/',
                    'password' => 'nullable|min:6|max:12|regex:/^[\S]{6,12}$/',
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            'name.required' => '请填写服务商名称',
            'email.required' => '请填写邮箱',
            'email.email' => '邮箱格式错误',
            'email.unique' => '邮箱已注册',
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确',
            'password.required' => '密码不能为空',
            'password.min' => '请输入6-12位的密码',
            'password.max' => '请输入6-12位的密码',
            'password.regex' => '密码不能出现空格',
        ];
    }

    public function failedValidation( \Illuminate\Contracts\Validation\Validator $validator ) {
        exit(json_encode(array(
            'success' => false,
            'code' => 1,
            'msg' => $validator->getMessageBag()->first(),
        )));
    }

    public function getA(Request $request)
    {
        $path = $request->route()->getAction();
        $act = substr($path['controller'], stripos($path['controller'], '@') + 1);
        return $act;
    }
}
