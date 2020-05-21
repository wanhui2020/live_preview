<?php

return [
    'name'            => env('APP_NAME', 'Laravel'),
    'env'             => env('APP_ENV', 'production'),
    'debug'           => env('APP_DEBUG', false),
    'url'             => env('APP_URL', 'http://localhost'),
    'asset_url'       => env('ASSET_URL', null),
    'logo'            => env('LOGO', env('USER_HEAD_PIC')),
    'timezone'        => 'Asia/Shanghai',
    'locale'          => 'zh-CN',
    'fallback_locale' => 'en',
    'faker_locale'    => 'en_US',
    'key'             => env('APP_KEY'),
    'cipher'          => 'AES-256-CBC',
    'copyright'       => env('APP_COPYRIGHT'),

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */
        Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        Spatie\Permission\PermissionServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\RiskServiceProvider::class,
        App\Providers\OssServiceProvider::class,
        App\Providers\BaseServiceProvider::class,
        App\Providers\CommonServiceProvider::class,
        App\Providers\PlatformServiceProvider::class,
        App\Providers\DealServiceProvider::class,
        App\Providers\MemberServiceProvider::class,
        App\Providers\ImServiceProvider::class,
        App\Providers\PushServiceProvider::class,
        App\Providers\PayServiceProvider::class,
        App\Providers\HcPayServiceProvider::class,
        App\Providers\HyPayServiceProvider::class,
        App\Providers\WalletServiceProvider::class,
        App\Providers\RiskServiceProvider::class,
        App\Providers\AliyunServiceProvider::class,
        App\Providers\WechatServiceProvider::class,
        App\Providers\GreenServiceProvider::class,
        App\Providers\LiveServiceProvider::class,
        App\Providers\MapServiceProvider::class,
        App\Providers\VodServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App'          => Illuminate\Support\Facades\App::class,
        'Arr'          => Illuminate\Support\Arr::class,
        'Artisan'      => Illuminate\Support\Facades\Artisan::class,
        'Auth'         => Illuminate\Support\Facades\Auth::class,
        'Blade'        => Illuminate\Support\Facades\Blade::class,
        'Broadcast'    => Illuminate\Support\Facades\Broadcast::class,
        'Bus'          => Illuminate\Support\Facades\Bus::class,
        'Cache'        => Illuminate\Support\Facades\Cache::class,
        'Config'       => Illuminate\Support\Facades\Config::class,
        'Cookie'       => Illuminate\Support\Facades\Cookie::class,
        'Crypt'        => Illuminate\Support\Facades\Crypt::class,
        'DB'           => Illuminate\Support\Facades\DB::class,
        'Eloquent'     => Illuminate\Database\Eloquent\Model::class,
        'Event'        => Illuminate\Support\Facades\Event::class,
        'File'         => Illuminate\Support\Facades\File::class,
        'Gate'         => Illuminate\Support\Facades\Gate::class,
        'Hash'         => Illuminate\Support\Facades\Hash::class,
        'Lang'         => Illuminate\Support\Facades\Lang::class,
        'Log'          => Illuminate\Support\Facades\Log::class,
        'Mail'         => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password'     => Illuminate\Support\Facades\Password::class,
        'Queue'        => Illuminate\Support\Facades\Queue::class,
        'Redirect'     => Illuminate\Support\Facades\Redirect::class,
        'Redis'        => Illuminate\Support\Facades\Redis::class,
        'Request'      => Illuminate\Support\Facades\Request::class,
        'Response'     => Illuminate\Support\Facades\Response::class,
        'Route'        => Illuminate\Support\Facades\Route::class,
        'Schema'       => Illuminate\Support\Facades\Schema::class,
        'Session'      => Illuminate\Support\Facades\Session::class,
        'Storage'      => Illuminate\Support\Facades\Storage::class,
        'Str'          => Illuminate\Support\Str::class,
        'URL'          => Illuminate\Support\Facades\URL::class,
        'Validator'    => Illuminate\Support\Facades\Validator::class,
        'View'         => Illuminate\Support\Facades\View::class,

        'BaseFacade'     => \App\Facades\BaseFacade::class,
        'PlatformFacade' => \App\Facades\PlatformFacade::class,
        'DealFacade'     => \App\Facades\DealFacade::class,
        'MemberFacade'   => \App\Facades\MemberFacade::class,
        'ImFacade'       => \App\Facades\ImFacade::class,
        'PushFacade'     => \App\Facades\PushFacade::class,
        'PayFacade'      => \App\Facades\PayFacade::class,
        'WalletFacade'   => \App\Facades\WalletFacade::class,
        'RiskFacade'     => \App\Facades\RiskFacade::class,
        'AliyunFacade'   => \App\Facades\AliyunFacade::class,
        'WechatFacade'   => \App\Facades\WechatFacade::class,
        'GreenFacade'    => \App\Facades\GreenFacade::class,
        'LiveFacade'     => \App\Facades\LiveFacade::class,
        'MapFacade'      => \App\Facades\MapFacade::class,
        'VodFacade'      => \App\Facades\VodFacade::class,
        'Image' => Intervention\Image\Facades\Image::class
    ],

];
