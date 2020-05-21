<?php

return [
    'verify_code_template_id'    => env('RISK_SMS_VERIFY_CODE_TEMPLATE_ID',
        154),
    'verify_code_expire_seconds' => 60,
    // 管理员验证码
    'admin_verification_code'    => 666777,
];
