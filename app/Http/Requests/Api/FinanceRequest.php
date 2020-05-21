<?php

namespace App\Http\Requests\Api;

use App\Facades\BaseFacade;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class FinanceRequest extends FormRequest
{
    public function platConfig($key)
    {
        return BaseFacade::platform($key);
    }

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
            case '/api/customer/finance/recharge':
                return [
                    'money' => 'required|gte:' . $this->platConfig('min_charge'),
                    'type' => 'required|numeric',
                    'payment_account' => 'nullable',
                    'remark' => 'nullable|max:255',
                    'voucher' => 'nullable',
                ];
                break;
            case '/api/customer/finance/withdraw':
                return [
                    'money' => 'required|gte:' . $this->platConfig('min_withdraw'). '|max:' . $this->platConfig('once_withdraw_money'),
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
            'money.required' => '请填写充值金额',
            'money.gte' => $this->type(),
            'money.max' => '单笔最大提现金额不能超过' . $this->platConfig('once_withdraw_money'),
            'type.required' => '请选择充值类型',
            'type.numeric' => '充值类型不合法',
            'remark.max' => '备注信息不能超过255个字符',
            'security_code.required' => '请填写安全码',
            'security_code.digits' => '安全码必须为6位的数字',
        ];
    }


    /**
     *  根据类型提示相应信息
     * @return string
     */
    public function type()
    {
        if (Request::getPathInfo() == '/api/customer/finance/recharge') {
            return '充值金额不能小于' . $this->platConfig('min_charge');
        } else {
            return '提现金额不能小于' . $this->platConfig('min_withdraw');
        }
    }
}
