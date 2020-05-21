<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;

class CustomerUserRequest extends FormRequest
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
                    'realname' => 'nullable',
                    'phone' => 'required|regex:/^1\d{10}$/|unique:customer_users',
                    'password' => 'required|min:6|max:12|regex:/^[\S]{6,12}$/',
                    'idcard' => 'nullable|between:15,18|unique:customer_users',
                    'bank_card' => 'nullable|regex:/^\d{16,19}$/',
                    'bank_mobile' => 'nullable|regex:/^1\d{10}$/',
                    'bank_address' => 'nullable',
                ];
            case 'update':
                return [
                    'realname' => 'nullable',
                    'phone' => 'nullable|regex:/^1\d{10}$/|unique:customer_users,phone,'.$request->id,
                    'password' => 'nullable|min:6|max:12|regex:/^[\S]{6,12}$/',
                    'idcard' => 'nullable|between:15,18|unique:customer_users,idcard,'.$request->id,
                    'bank_card' => 'nullable|regex:/^\d{16,19}$/',
                    'bank_mobile' => 'nullable|regex:/^1\d{10}$/',
                    'bank_address' => 'nullable',
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            'realname.required' => '请填写真实姓名',
            //'realname.regex' => '姓名输入不合法,请重新输入',
            'phone.required' => '请填写手机号',
            'phone.unique' => '手机号已注册',
            'phone.regex' => '手机号格式不正确',
            'password.required' => '密码不能为空',
            'password.min' => '请输入6-12位的密码',
            'password.max' => '请输入6-12位的密码',
            'password.regex' => '密码不能出现空格',
            'idcard.between' => '请填写15或18位正确的身份证号',
            'idcard.unique' => '此身份证已实名',
            'bank_card.regex' => '请填写16戓19位的银行卡号',
            'bank_mobile.regex' => '预留电话格式不准确',
        ];
    }

    public function failedValidation(Validator $validator ) {
        exit(json_encode(array(
            'status' => false,
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
