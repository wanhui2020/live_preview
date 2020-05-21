<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 会员管理
 * Class CreateMemberUsersTable
 */
class CreateMemberUsersTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('member_users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('no')->unique()->comment('会员编号');
            $table->unsignedTinyInteger('type')->default(0)->comment('会员类型0普通用户1陪聊2客服');

            $table->unsignedInteger('level_id')->default(0)->comment('所属等级ID');
            $table->unsignedInteger('group_id')->default(0)->comment('所属分组ID');
            $table->unsignedTinyInteger('is_middleman')->default(1)->comment('是否经济人0是1不是');
            $table->unsignedInteger('agent_id')->default(0)->comment('所属代理商ID(0无代理商)');
            $table->unsignedInteger('parent_id')->default(0)->comment('推荐人');
            $table->string('invite_code')->unique()->comment('邀请码(注册时自动生成)');
            $table->unsignedInteger('service_id')->default(0)->comment('我的客服');

            $table->string('username')->nullable()->comment('用户名');
            $table->string('mobile', 11)->nullable()->comment('手机号码');
            $table->unsignedTinyInteger('mobile_verify')->default(9)->comment('手机号验证 0通过 1失败9未知');

            $table->string('password')->nullable()->comment('登录密码');
            $table->string('capital_password')->nullable()->comment('资金密码');

            $table->string('nick_name')->nullable()->comment('昵称');
            $table->string('head_pic')->nullable()->comment('头像图片');
            $table->string('cover')->nullable()->comment('封面图片');

            $table->unsignedTinyInteger('sex')->default(9)->comment('姓别 0男 1女9未知');
            $table->date('birthday')->nullable()->comment('生日');

            $table->string('resident')->nullable()->comment('常驻城市');
            $table->string('province')->nullable()->comment('所在省');
            $table->string('city')->nullable()->comment('所在市城市');
            $table->string('district')->nullable()->comment('所在县区');
            $table->string('lat')->nullable()->comment('经度');
            $table->string('lng')->nullable()->comment('纬度');
            $table->string('address')->nullable()->comment('详细地址');


            $table->string('aphorism')->nullable()->comment('格言');
            $table->string('signature')->nullable()->comment('签名');

            $table->unsignedInteger('charm_grade')->default(0)->comment('魅力等级');
            $table->unsignedInteger('charm_integral')->default(0)->comment('魅力积分');
            $table->unsignedInteger('vip_grade')->default(0)->comment('VIP等级');
            $table->unsignedInteger('vip_integral')->default(0)->comment('VIP积分');

            $table->unsignedInteger('vip_id')->default(0)->comment('vip等级');
            $table->date('vip_end')->nullable()->comment('vip到期日期');

            $table->dateTime('last_time')->nullable()->comment('最后更新时间');
            $table->string('last_ip')->nullable()->comment('最后更新IP');

            $table->tinyInteger('hot')->default(0)->comment('热门推荐指数');
            $table->tinyInteger('online_status')->default(0)->comment('在线状态:0在线1离线9未知 ');
            $table->tinyInteger('im_status')->default(9)->comment('在线状态: 0在线1离线2休眠9未知');
            $table->tinyInteger('live_status')->default(0)->comment('语音视频是否忙碌(0空闲 1忙碌)');
            $table->tinyInteger('is_real')->default(9)->comment('是否实名认证 0已认证1未认证8审核中9待认证');
            $table->tinyInteger('is_selfie')->default(9)->comment('是否自拍认证 0认证通过 1认证拒绝 8审核中 9待认证');

            $table->string('weixin_openid', 50)->nullable()->comment('微信OPENID');
            $table->string('unionid')->nullable()->comment('微信开放平台OPENID');
            $table->string('im_token', 200)->unique()->nullable()->comment('三方imtoken');
            $table->string('push_token')->nullable()->comment('推送token');
            $table->string('api_token')->unique()->nullable()->comment('用户Token');

            $table->tinyInteger('theme_id')->default(0)->comment('用户主题');
            $table->string('app_platform')->nullable()->comment('APP平台android,ios');
            $table->string('app_version')->nullable()->comment('APP版本号');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0  正常1禁用');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unique(['mobile', 'unionid']);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_users');
    }
}
