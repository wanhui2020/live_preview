<nav class="navbar navbar-expand-md navbar-light navbar-laravel bg-light border-bottom">
    <div class="container-fluid" style="max-width: 1400px;">
        <a class="navbar-brand" href="{{ url('/agent') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item  ">
                    <a class="nav-link" href="{{url('/agent/deal')}}">交易中心</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{url('/agent/currency')}}">代币管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/agent/legal')}}">法币管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/agent/base/user/info')}}">服务商管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/agent/report/home')}}">统计报表</a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user('AgentUser')->name }}{{Auth::user('AgentUser')->audit_status==0?'':'（实名中）'}} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{url('agent/base/user/info') }}"
                         >
                            个人信息
                        </a>
                        <a class="dropdown-item" href="{{url('agent/logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            退出
                        </a>

                        <form id="logout-form" action="  {{url('agent/logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>