@extends('layouts.base')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md8">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md6">
                        <div class="layui-card">
                            <div class="layui-card-header">快捷方式</div>
                            <div class="layui-card-body">

                                <div class="layui-carousel layadmin-carousel layadmin-shortcut">
                                    <div>
                                        <ul class="layui-row layui-col-space10">

                                            <li class="layui-col-xs3">
                                                <a lay-href="system/deal/talk">
                                                    <i class="layui-icon layui-icon-survey"></i>
                                                    <cite>通话订单</cite>
                                                </a>
                                            </li>

                                            <li class="layui-col-xs3">
                                                <a lay-href="system/member/user">
                                                    <i class="layui-icon layui-icon-user"></i>
                                                    <cite>会员管理</cite>
                                                </a>
                                            </li>

                                            <li class="layui-col-xs3">
                                                <a>
                                                    <i class="layui-icon layui-icon-chat"></i>
                                                    <cite>聊天</cite>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs3">
                                                <a lay-href="system/member/wallet">
                                                    <i class="layui-icon layui-icon-find-fill"></i>
                                                    <cite>钱包账户</cite>
                                                </a>
                                            </li>

                                            <li class="layui-col-xs3">
                                                <a lay-href="system/member/wallet/recharge">
                                                    <i class="layui-icon layui-icon-template-1"></i>
                                                    <cite>充值管理</cite>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs3">
                                                <a lay-href="system/member/wallet/withdraw">
                                                    <i class="layui-icon layui-icon-chart"></i>
                                                    <cite>提现管理</cite>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs3">
                                                <a lay-href="system/platform/config">
                                                    <i class="layui-icon layui-icon-set"></i>
                                                    <cite>平台参数</cite>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md6">
                        <div class="layui-card">
                            <div class="layui-card-header">待办事项</div>
                            <div class="layui-card-body">
                                <div class="layui-carousel layadmin-carousel layadmin-backlog">
                                    <div>
                                        <ul class="layui-row layui-col-space10">
                                            <li class="layui-col-xs6">
                                                <a lay-href="{{url('/system/member/resource')}}"
                                                   class="layadmin-backlog-body">
                                                    <h3>待审封面</h3>
                                                    <p><cite>{{$resource['coverCount']}}</cite></p>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs6">
                                                <a lay-href="{{url('/system/member/resource')}}"
                                                   class="layadmin-backlog-body">
                                                    <h3>待审照片</h3>
                                                    <p><cite>{{$resource['pictureCount']}}</cite></p>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs6">
                                                <a lay-href="{{url('/system/member/resource')}}"
                                                   class="layadmin-backlog-body">
                                                    <h3>待审视频</h3>
                                                    <p><cite>{{$resource['videoCount']}}</cite></p>
                                                </a>
                                            </li>
                                            <li class="layui-col-xs6">
                                                <a lay-href="{{url('/system/member/resource')}}"
                                                   class="layadmin-backlog-body">
                                                    <h3>待审资料</h3>
                                                    <p><cite>-</cite></p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-sm6 layui-col-md4">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                在线情况
                            </div>
                            <div class="layui-card-body layuiadmin-card-list">
                                <p> 在线用户： {{$member['onlineStatus']}} </p>
                                <p> 忙碌用户： {{$member['liveStatus']}} </p>
                                <p> 视频在线： {{$member['imStatus']}} </p>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-sm6 layui-col-md4">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                会员信息
                            </div>
                            <div class="layui-card-body layuiadmin-card-list">
                                <p> 有效会员：{{$member['memberCount']}} </p>
                                <p> 实名会员：{{$member['realStatus']}} </p>
                                <p> 认证会员：{{$member['selfieStatus']}} </p>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-sm6 layui-col-md4">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                资源待审-{{$resource['totalCount']}}
                            </div>
                            <div class="layui-card-body layuiadmin-card-list">
                                <p> 封面： {{$resource['coverCount']}}</p>
                                <p> 照片： {{$resource['pictureCount']}}</p>
                                <p> 视频： {{$resource['videoCount']}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-tab layui-tab-brief  ">
                                <ul class="layui-tab-title">
                                    <li class="layui-this">今日头牌</li>
                                    <li>热门会员</li>
                                </ul>
                                <div class="layui-tab-content">
                                    <div class="layui-tab-item layui-show">
                                        @foreach($hots as $item)
                                            <a lay-href="system/member/user/detail?id={{$item->id}}"
                                               style="width:80px;height:80px;display: inline-block;text-align: center;margin: 5px;display: inline-block">
                                                <img src="{{$item->fill_head_pic}}"
                                                     style="width:60px;height:60px;display: block">
                                                <cite>{{$item->nick_name}}</cite>
                                            </a>
                                        @endforeach
                                    </div>
                                    <div class="layui-tab-item">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md4">
                <div class="layui-card">
                    <div class="layui-card-header">平台信息</div>
                    <div class="layui-card-body layui-text">
                        <table class="layui-table">
                            <colgroup>
                                <col width="100">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>在线用户</td>
                                <td>
                                    {{$member['onlineStatus']}}
                                </td>
                            </tr>
                            <tr>
                                <td>视频在线</td>
                                <td>
                                    {{$member['liveStatus']}}
                                </td>
                            </tr>
                            <tr>
                                <td>忙碌用户</td>
                                <td>{{$member['imStatus']}}</td>
                            </tr>

                            </tbody>
                        </table>
                        <table class="layui-table">
                            <colgroup>
                                <col width="100">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>今日会员</td>
                                <td>
                                    {{\App\Models\MemberUser::whereDate('created_at',now())->where('type',0)->count()}}
                                </td>
                            </tr>
                            <tr>
                                <td>合计会员</td>
                                <td>
                                    {{\App\Models\MemberUser::where('type',0)->count()}}
                                </td>
                            </tr>
                            <tr>
                                <td>今日充值</td>
                                <td>
                                    {{\App\Models\MemberWalletRecharge::whereDate('created_at',now())->where('pay_status',0)->sum('money')}}

                                </td>
                            </tr>
                            <tr>
                                <td>合计充值</td>
                                <td>
                                    {{\App\Models\MemberWalletRecharge::where('pay_status',0)->sum('money')}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="layui-card">
                    <div class="layui-card-header">常用功能</div>
                    <div class="layui-card-body layadmin-takerates">
                        <input type="button" class="layui-btn layui-btn-normal" lay-submit
                               lay-filter="grade"
                               value="等级计算">
                        <input type="button" class="layui-btn layui-btn-normal" lay-submit
                               lay-filter="cache"
                               value="清除缓存">
                        <input type="button" class="layui-btn layui-btn-normal" lay-submit
                               lay-filter="push_tag"
                               value="更新推送标签">
                    </div>
                </div>


            </div>

        </div>
    </div>
@endsection
@section('script')
    <script type="application/javascript">
        layui.config({
            base: '{{ asset('plugins/layuiadmin')}}/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use('index', 'console');

        layui.use(['form', 'upload'], function () {
            let $ = layui.$
                , form = layui.form;

            form.on('submit(grade)', function (data) {
                    layer.confirm('确认重新计算等级？', function (index) {
                        layer.close(index);
                        let field = data.field;
                        layer.load();
                        axios.post("{{url('system/grade/sync')}}", field)
                            .then(function (response) {
                                layer.closeAll();
                                if (response.data.status) {
                                    return layer.msg(response.data.msg);
                                }
                                return layer.msg(response.data.msg);
                            });
                    });

                }
            );
            form.on('submit(cache)', function (data) {
                    layer.confirm('确认清除缓存？', function (index) {
                        layer.close(index);
                        let field = data.field;
                        layer.load();
                        axios.post("{{url('system/cache/clear')}}", field)
                            .then(function (response) {
                                layer.closeAll();
                                if (response.data.status) {
                                    return layer.msg(response.data.msg);
                                }
                                return layer.msg(response.data.msg);
                            });
                    });

                }
            );
            form.on('submit(push_tag)', function (data) {
                    layer.confirm('确认更新？', function (index) {
                        layer.close(index);
                        let field = data.field;
                        layer.load();
                        axios.post("{{url('system/push_tag')}}", field)
                            .then(function (response) {
                                layer.closeAll();
                                if (response.data.status) {
                                    return layer.msg(response.data.msg);
                                }
                                return layer.msg(response.data.msg);
                            });
                    });

                }
            );


        });
    </script>
@endsection

