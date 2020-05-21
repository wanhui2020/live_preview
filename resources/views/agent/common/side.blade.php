
@if($sub=='deal')
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            交易中心
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="{{url('/agent/deal/entrust')}}" class="card-link">委托记录</a></li>
            <li class="list-group-item"><a href="{{url('/agent/deal/order')}}" class="card-link">订单记录</a></li>
            <li class="list-group-item"><a href="{{url('/agent/deal/appeal')}}" class="card-link">申诉记录</a></li>

        </ul>
    </div>
@endif
@if($sub=='currency')
    <div class="card mb-4 border-bottom">
        <div class="card-header bg-success text-white">
            代币管理
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="{{url('/agent/currency/wallet')}}" class="card-link">代币账户</a></li>
            <li class="list-group-item"><a href="{{url('/agent/currency/recharge')}}" class="card-link">充值转入</a></li>
            <li class="list-group-item"><a href="{{url('/agent/currency/withdraw')}}" class="card-link">提现转出</a></li>
        </ul>
    </div>
@endif
@if($sub=='legal')
    <div class="card mb-4 border-bottom">
        <div class="card-header bg-success text-white">
            法币管理
        </div>
        <ul class="list-group list-group-flush">
{{--            <li class="list-group-item"><a href="{{url('/agent/legal/wallet')}}" class="card-link">法币账户</a></li>--}}
            <li class="list-group-item"><a href="{{url('/agent/legal/payee')}}" class="card-link">收款账户</a></li>
        </ul>
    </div>
@endif

@if($sub=='agent')
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            服务商管理
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="{{url('/agent/base/user/info')}}" class="card-link">我的特权</a></li>
            <li class="list-group-item"><a href="{{url('/agent/base/merchant')}}" class="card-link">我的商户</a></li>
            <li class="list-group-item"><a href="{{url('/agent/base/otc')}}" class="card-link">我的承兑商</a></li>
        </ul>
    </div>    @endif

@if($sub=='message')
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            信息中心
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="{{url('/agent/config')}}" class="card-link">站内信息</a></li>
            <li class="list-group-item"><a href="{{url('/agent/payee')}}" class="card-link">收款账户</a></li>
            <li class="list-group-item"><a href="{{url('/agent/contact')}}" class="card-link">联系方式</a></li>

        </ul>
    </div>
@endif
@if($sub=='report')
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            统计报表
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="{{url('/agent/report/home')}}" class="card-link">业务报表</a></li>
            <li class="list-group-item"><a href="{{url('/agent/report/merchant')}}" class="card-link">按商户</a></li>
            <li class="list-group-item"><a href="{{url('/agent/report/settle')}}" class="card-link">商户结算</a></li>
            <li class="list-group-item"><a href="{{url('/agent/report/member')}}" class="card-link">按承兑商</a></li>

        </ul>
    </div>
@endif