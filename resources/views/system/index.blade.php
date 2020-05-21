<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{BaseFacade::config('name')}}</title>
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/style/admin.css')}}" media="all">

    <script>
        /^http(s*):\/\//.test(location.href) || alert('请先部署到 localhost 下再访问');
    </script>
</head>
<body class="layui-layout-body">

<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="/" target="_blank" title="前台">
                        <i class="layui-icon layui-icon-website"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;" layadmin-event="refresh" title="刷新">
                        <i class="layui-icon layui-icon-refresh-3"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search"
                           layadmin-event="serach" lay-action="system/search?keywords=">
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

                <li class="layui-nav-item" lay-unselect>
                    <a lay-href="{{url('system/platform/message')}}" layadmin-event="message" lay-text="消息中心">
                        <i class="layui-icon layui-icon-notice"></i>
                        <!-- 如果有新消息，则显示小圆点 -->
                        {{--<span class="layui-badge-dot"></span>--}}
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="theme">
                        <i class="layui-icon layui-icon-theme"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="note">
                        <i class="layui-icon layui-icon-note"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="fullscreen">
                        <i class="layui-icon layui-icon-screen-full"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;">
                        <cite>{{Auth::user('SystemUser')->name}} </cite>
                    </a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="{{url('system/base/user/info')}}">基本资料</a></dd>
                        <hr>
                        <dd style="text-align: center;"><a href="#" id="logout">退出</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="about"><i
                            class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
                <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
                    <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
            </ul>
        </div>

        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" lay-href="/system/home/">
                    <span>{{env('APP_NAME')}}</span>
                </div>
                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu"
                    lay-filter="layadmin-system-side-menu">

                    <li data-name="agent" class="layui-nav-item  ">
                        <a href="javascript:;" lay-tips="会员中心" lay-direction="3">
                            <i class="layui-icon  layui-icon-user"></i>
                            <cite>会员中心</cite>
                        </a>
                        @can('会员中心/会员管理')
                            <dl class="layui-nav-child">
                                <dd data-name="memberuser">
                                    <a lay-href="{{url('system/member/user')}}">会员管理</a>
                                </dd>
                            </dl>
                        @endcan
                        @can('会员中心/实名认证')
                            <dl class="layui-nav-child">
                                <dd data-name="memberuser">
                                    <a lay-href="{{url('system/member/user/realname')}}">实名认证</a>
                                </dd>
                            </dl>
                        @endcan
                        @can('会员中心/自拍认证')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/user/selfie')}}">自拍认证</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员中心/资料审核')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/verification')}}">资料审核</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员中心/扩展审核')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/user/extend')}}">扩展审核</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员中心/会员费率')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/user/rate')}}">会员费率</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员中心/会员参数')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/user/parameter')}}">会员参数</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员中心/访问记录')
                            <dl class="layui-nav-child">
                                <dd data-name="memberuser">
                                    <a lay-href="{{url('system/member/visitor')}}">访问记录</a>
                                </dd>
                            </dl>
                        @endcan
                        @can('会员中心/登录日志')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/login')}}">登录日志</a>
                            </dd>
                        </dl>
                        @endcan

                    </li>

                    <li data-name="user" class="layui-nav-item  ">
                        <a href="javascript:;" lay-tips="会员行为" lay-direction="3">
                            <i class="layui-icon  layui-icon-find-fill"></i>
                            <cite>会员行为</cite>
                        </a>
                        @can('会员行为/会员关注')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/attention')}}">会员关注</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员行为/会员好友')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/friend')}}">会员好友</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员行为/意见反馈')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/feedback')}}">意见反馈</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员行为/会员举报')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/report')}}">会员举报</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员行为/会员签到')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/signin')}}">会员签到</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员行为/会员资源')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/resource')}}">会员资源</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('会员行为/会员动态')
                            <dl class="layui-nav-child">
                                <dd data-name="memberuser">
                                    <a lay-href="{{url('system/member/social')}}">会员动态</a>
                                </dd>
                            </dl>
                        @endcan
                        @can('会员行为/黑名单')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/blacklist')}}">黑名单</a>
                            </dd>
                        </dl>
                        @endcan
                    </li>
                    <li data-name="user" class="layui-nav-item  ">
                        <a href="javascript:;" lay-tips="交易管理" lay-direction="3">
                            <i class="layui-icon  layui-icon-template-1"></i>
                            <cite>交易管理</cite>
                        </a>
                        @can('交易管理/语音视频')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/talk')}}">语音视频</a>
                            </dd>
                        </dl>
                        @endcan
                        {{--                        <dl class="layui-nav-child">--}}
                        {{--                            <dd data-name="memberuser">--}}
                        {{--                                <a lay-href="{{url('system/deal/unlock')}}">聊天解锁</a>--}}
                        {{--                            </dd>--}}
                        {{--                        </dl>--}}
                        @can('交易管理/聊天信息')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/message')}}">聊天信息</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('交易管理/资源查看')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/view')}}">资源查看</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('交易管理/礼物赠送')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/gift')}}">礼物赠送</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('交易管理/社交动态')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/social')}}">社交动态</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('交易管理/评论管理')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/comment')}}">评论管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('交易管理/会员点赞')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/like')}}">会员点赞</a>
                            </dd>
                        </dl>
                        @endcan

                        @can('交易管理/微信购买')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/member/wallet/record?type=52,53')}}">微信购买</a>
                            </dd>
                        </dl>
                        @endcan
                        {{--                        <dl class="layui-nav-child">--}}
                        {{--                            <dd data-name="memberuser">--}}
                        {{--                                <a lay-href="{{url('system/deal/give')}}">会员打赏</a>--}}
                        {{--                            </dd>--}}
                        {{--                        </dl>--}}
                        {{--                        <dl class="layui-nav-child">--}}
                        {{--                            <dd data-name="memberuser">--}}
                        {{--                                <a lay-href="{{url('system/deal/vip')}}">VIP购买</a>--}}
                        {{--                            </dd>--}}
                        {{--                        </dl>--}}

                    </li>
                    <li data-name="user" class="layui-nav-item  ">
                        <a href="javascript:;" lay-tips="钱包管理" lay-direction="3">
                            <i class="layui-icon  layui-icon-rmb"></i>
                            <cite>钱包管理</cite>
                        </a>
                        @can('钱包管理/能量钱包')
                            <dl class="layui-nav-child">
                                <dd data-name="console">
                                    <a lay-href="{{url('system/member/wallet/gold')}}">能量钱包</a>
                                </dd>
                            </dl>
                        @endcan
                        @can('钱包管理/金币钱包')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/member/wallet/cash')}}">金币钱包</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('钱包管理/能量充值')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/gold')}}">能量充值</a>
                            </dd>
                        </dl>
                        @endcan

                        {{--                        <dl class="layui-nav-child">--}}
                        {{--                            <dd data-name="memberuser">--}}
                        {{--                                <a lay-href="{{url('system/deal/conversion')}}">兑换余额</a>--}}
                        {{--                            </dd>--}}
                        {{--                        </dl>--}}
                        @can('钱包管理/余额充值')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/cash')}}">余额充值</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('钱包管理/提现')
                        <dl class="layui-nav-child">
                            <dd data-name="memberuser">
                                <a lay-href="{{url('system/deal/withdraw')}}">提现</a>
                            </dd>
                        </dl>
                        @endcan
                    </li>
                    <li data-name="user" class="layui-nav-item  ">
                        <a href="javascript:;" lay-tips="财务管理" lay-direction="3">
                            <i class="layui-icon layui-icon-form"></i>
                            <cite>财务管理</cite>
                        </a>

                        @can('财务管理/钱包账户')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/member/wallet')}}">钱包账户</a>
                            </dd>
                        </dl>
                        @endcan

                        @can('财务管理/充值转入')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/member/wallet/recharge')}}">充值转入</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('财务管理/提现转出')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/member/wallet/withdraw')}}">提现转出</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('财务管理/资金明细')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/member/wallet/record')}}">资金明细</a>
                            </dd>
                        </dl>
                        @endcan
                    </li>
                    <li data-name="user" class="layui-nav-item  ">
                        <a href="javascript:;" lay-tips="平台设置" lay-direction="3">
                            <i class="layui-icon  layui-icon-engine"></i>
                            <cite>平台设置</cite>
                        </a>
                        @can('平台设置/平台参数')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/config')}}">平台参数</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/基础数据')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/basic')}}">基础数据</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/关键字')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/keyword')}}">关键字</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/平台参数')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/tag')}}">标签管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/自定义信息管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/send')}}">自定义信息管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/礼物管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/gift')}}">礼物管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/VIP管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/vip')}}">VIP管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/魅力管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/charm')}}">魅力管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/支付通道')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/payment')}}">支付通道</a>
                            </dd>
                        </dl>

                        @endcan
                        @can('平台设置/价格维护')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/price')}}">价格维护</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/新闻公告')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/message')}}">新闻公告</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/系统通知')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/notice')}}">系统通知</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/文本维护')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/text')}}">文本维护</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/版本管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/edition')}}">版本管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/首页类型管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/type')}}">首页类型管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('平台设置/筛选条件管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/condition')}}">筛选条件管理</a>
                            </dd>
                        </dl>
                        @endcan
                    </li>
                    <li data-name="user" class="layui-nav-item  ">
                        <a href="javascript:;" lay-tips="系统设置" lay-direction="3">
                            <i class="layui-icon layui-icon-set-fill"></i>
                            <cite>系统设置</cite>
                        </a>
                        @can('系统设置/系统参数')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/base/config')}}">系统参数</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('系统设置/用户管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/base/user')}}">用户管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('系统设置/角色管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/base/role')}}">角色管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('系统设置/权限管理')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/base/permission')}}">权限管理</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('系统设置/系统日志')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/logs')}}">系统日志</a>
                            </dd>
                        </dl>

                        {{--<dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/base/logs/business')}}">业务日志</a>
                            </dd>
                        </dl>--}}
                        @endcan
                        @can('系统设置/登录日志')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/base/logs/logins')}}">登录日志</a>
                            </dd>
                        </dl>
                        @endcan
                        @can('系统设置/env配置')
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/platform/env/edit')}}">env配置</a>
                            </dd>
                        </dl>
                        @endcan
                    </li>

                    <li data-name="user" class="layui-nav-item  ">
                        <a href="javascript:;" lay-tips="统计报表" lay-direction="3">
                            <i class="layui-icon layui-icon-set-fill"></i>
                            <cite>统计报表</cite>
                        </a>
                        @can('统计报表/用户报表')
                            <dl class="layui-nav-child">
                                <dd data-name="console">
                                    <a lay-href="{{url('system/member/user/report_form')}}">用户报表</a>
                                </dd>
                            </dl>
                        @endcan
                    </li>
                    {{--<li data-name="user" class="layui-nav-item  ">
                        <a href="javascript:;" lay-tips="统计报表" lay-direction="3">
                            <i class="layui-icon  layui-icon-find-fill"></i>
                            <cite>统计报表</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/report/order/merchant')}}">商户订单</a>
                            </dd>
                        </dl>
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/report/order/otc')}}">承兑商订单</a>
                            </dd>
                        </dl>
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('system/report/order/platform')}}">平台报表</a>
                            </dd>
                        </dl>
                    </li>--}}

                </ul>
            </div>
        </div>

        <!-- 页面标签 -->
        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-down">
                <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li lay-id="home/console.html" lay-attr="home/console.html" class="layui-this"><i
                            class="layui-icon layui-icon-home"></i></li>
                </ul>
            </div>
        </div>


        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe name="child" src="{{url('system/home')}}" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>

        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>

<script src="{{ asset('js/app.js')}}"></script>
<script src="{{ asset('plugins/layuiadmin/layui/layui.js')}}"></script>
<script>
    let element;
    layui.config({
        base: '{{ asset('plugins/layuiadmin')}}/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'element'], function () {
        element = layui.element;
        let $ = layui.$;
        // element.on('tab(layadmin-layout-tabs)', function(data){
        //     console.log(this); //当前Tab标题所在的原始DOM元素
        //     console.log(data.index); //得到当前Tab的所在下标
        //     console.log(data.elem); //得到当前的Tab大容器
        // });
        $(document).on('click', '#logout', function () {
            axios.post("{{url('system/logout')}}")
                .then(function (response) {
                        console.log(response.data);
                        if (response.data.status) {
                            layer.msg('退出成功', {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            }, function () {
                                location.href = '{{url("system/login")}}'; //跳转到登入页
                            });

                        } else {
                            layer.msg('请求失败', response.data.msg);
                        }

                    }
                ).catch(function (err) {

            });

        });
    });

    // Echo.private('entrust')
    //     .listen('.deal.entrust', (e) => {
    //         console.log(123);
    //     });
</script>
</body>
</html>


