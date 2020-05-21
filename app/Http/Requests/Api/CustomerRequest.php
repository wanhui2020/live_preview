<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CustomerRequest extends FormRequest
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
        switch (Request::getPathInfo()) {
            case '/api/customer/update':
                return [
                    'realname' => 'nullable',
                    'idcard' => 'nullable|between:15,18|unique:customer_users,idcard,'.$request->user()->id,
                    'bank_card' => 'nullable|regex:/^\d{16,19}$/',
                    'bank_mobile' => 'nullable|regex:/^1\d{10}$/',
                    'bank_address' => 'nullable',
                ];
                break;
            case '/api/customer/setsafecode':
                return [
                    'code' => 'required|digits:4',
                    'security_code' => 'required|digits:6',
                ];
                break;
            case '/api/customer/verifysafecode':
                return [
                    'security_code' => 'required|digits:6',
                ];
                break;
            case '/api/customer/unbind':
                return [
                    'security_code' => 'required|digits:6',
                ];
                break;
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            //'realname.regex' => '用户名不合法，请重新输入',
            'code.required' => '请填写验证码',
            'security_code.required' => '请填写安全码',
            'code.digits' => '验证码必须为4位的数字',
            'security_code.digits' => '安全码必须为6位的数字',
            'idcard.between' => '请填写15或18位正确的身份证号',
            'idcard.unique' => '此身份证已实名',
            'bank_card.regex' => '请填写16戓19位的银行卡号',
            'bank_mobile.regex' => '预留电话格式不准确',
        ];
    }

}
