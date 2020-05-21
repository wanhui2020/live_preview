<?php

namespace App\Http\Requests\Api;

use App\Models\AppErrorLog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateLogRequest extends FormRequest
{

    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files,
            $server, $content);

        request()->request->set('platform', strtolower(request('platform')));
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
//    public function authorize()
//    {
//        return true;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'platform' => [
                'required',
                Rule::in([AppErrorLog::ANDROID, AppErrorLog::IOS]),
            ],
            'version'  => 'required',
            'content'  => 'required',
        ];
    }


}
