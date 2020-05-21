<?php

namespace App\Jobs;

use App\Facades\OssFacade;
use App\Models\MemberUser;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Image;
use QrCode;

class GenerateShareImageJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = MemberUser::find($this->userId);
        if (!$user) {
            return;
        }

        $qrcodePath = $this->generateQrCode($user);
        $shareImage = $this->generateShareImage(
            $qrcodePath,
            $user
        );

        $res = OssFacade::putUrl($shareImage);

        if (!$res['status']) {
            \Log::error("generate share image failed member_use_id: $this->userId");

            return;
        }

        File::delete($qrcodePath);
        File::delete($shareImage);
        $user->share_image = $res['src'];
        $user->save();
    }

    protected function generateQrCode($user)
    {
        $qrcodePath    = storage_path("app/public/qrcode_{$user->id}.png");
        $qrcodeContent = url('/share/'.$user->no);

        QrCode::format('png')
            ->margin(2)
            ->errorCorrection('H')
            ->size(480)
            ->merge(config('app.logo'), .2, true)
            ->generate($qrcodeContent, $qrcodePath);

        return $qrcodePath;
    }

    protected function generateShareImage($qrcodePath, $user)
    {
        $sharePng = storage_path("/app/public/share_{$user->id}.png");

        $user->head_pic = $user->head_pic ?: config('app.logo');
        $avatarImage    = Image::make($user->head_pic)
            ->resize(80, 80);

        $qrcode = Image::make($qrcodePath);

        $bg = Image::canvas(562, 800, '#e8e8e8');
        $bg->insert($avatarImage, 'top-center', 0, 20);

        $bg->text('我的ID：'.$user->no, 180, 120, function ($font) {
            $font->file(public_path('/font/YangRenDongZhengBangTi-2.ttf'));
            $font->size(24);
            $font->color('#696969');
            $font->valign('top');
        });

        $bg->insert($qrcode, 'bottom-left', 41, 100);
        $bg->text('扫描二维码下载APP', 180, 720, function ($font) {
            $font->file(public_path('/font/YangRenDongZhengBangTi-2.ttf'));
            $font->size(24);
            $font->color('#696969');
            $font->valign('top');
        });
        $bg->save($sharePng);

        return $sharePng;
    }
}
