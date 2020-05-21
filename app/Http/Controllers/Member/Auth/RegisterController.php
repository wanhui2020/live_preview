<?php

namespace App\Http\Controllers\Member\Auth;

use App\Models\MemberUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/otc';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('member.auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'mobile' => ['required', 'string',  'max:255'],
            'password' => ['required', 'string',  'confirmed'],
            'invite_code' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     */
    protected function create(array $data)
    {
        $user = [
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];
        $isMobileEmail= MemberUser::where('mobile', $data['mobile'])->orWhere('email',$data['email'])->first();
        if (isset($isMobileEmail)) {
            return $this->validation('手机号或邮箱重复！');
        }
       if (!isset( $data['invite_code'])){
           return $this->validation('邀请码不能为空！');
       }
        $parent = MemberUser::where('invite_code', $data['invite_code'])->first();
        if (isset($parent)) {
            $user['parent_id'] = $parent->id;
            $user['agent_id'] = $parent->agent_id;
        } else {
            $agent = AgentUser::where('invite_code', $data['invite_code'])->first();
            if (isset($agent)) {
                $user['agent_id'] = $agent->id;
            }else{
                return $this->validation('邀请码无效！');
            }
        }
        return MemberUser::create($user);
    }

    protected function registered(Request $request, $user)
    {
        return $this->succeed($user, '注册成功');
    }

    protected function guard()
    {
        return Auth::guard('MemberUser');
    }
}
