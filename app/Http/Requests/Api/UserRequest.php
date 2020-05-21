<?php

namespace App\Http\Requests\Api;

use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
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
    public function rules()
    {
        switch (Request::getPathInfo()) {
            case '/api/auth/login':
                return [
                    'phone' => 'required|regex:/^1\d{10}$/',
                    'password' => 'required|min:6|max:12|regex:/^[\S]{6,12}$/',
                ];
                break;
            case '/api/auth/register':
                return [
                    'phone' => 'required|regex:/^1\d{10}$/|unique:customer_users',
                    'password' => 'required|min:6|max:12|regex:/^[\S]{6,12}$/',
                    'invite_code' => 'required|digits:6',
                    'code' => 'required|digits:4',
                ];
                break;
            case '/api/auth/forget':
                return [
                    'phone' => 'required|regex:/^1\d{10}$/',
                    'code' => 'required|digits:4',
                    'password' => 'required|min:6|max:12|regex:/^[\S]{6,12}$/',
                ];
                break;
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            'phone.required' => '手机号不能为空',
            'phone.unique' => '手机号已注册',
            'phone.regex' => '手机号格式不正确',
            'invite_code.digits' => '邀请码必须为6位的数字',
            'code.digits' => '验证码必须为4位的数字',
            'password.required' => '密码不能为空',
            'password.min' => '请输入6-12位的密码',
            'password.max' => '请输入6-12位的密码',
            'password.regex' => '密码不能出现空格',
        ];
    }

}
