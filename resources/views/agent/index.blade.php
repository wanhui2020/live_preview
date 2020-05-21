@extends('layouts.agent')
@section('content')
    <div class="row ">
        <div class="col-sm-2">
            @include('agent.common.side',['page'=>'文章页','sub'=>'agent'])
        </div>
        <div class="col-sm-10">
            <div class="layui-card">
                <div class="layui-card-header ">
                    商户信息
                </div>
                <div class="layui-card-body">
                    <form>
                        <div class="form-group row">
                            <label for="no" class="col-sm-2 col-form-label">商户编号</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="no"
                                       v-model="agent.no">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">商户名称</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="name"
                                       v-model="agent.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mobile" class="col-sm-2 col-form-label">手机号</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="mobile"
                                       v-model="agent.mobile">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">邮箱</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" id="email"
                                       v-model="agent.email">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="application/javascript">

        let vm = new Vue({
            el: '#app',
            data: {
                agent: {!! \Illuminate\Support\Facades\Auth::user('AgentUser') !!},

            },
            created: function () {

                let self = this;


            },
            methods: {
                register: function () {
                    if (!this.agent.name) {
                        return layer.msg('商户名称不能为空')
                    }
                    if (!this.agent.email) {
                        return layer.msg('邮箱不能为空')
                    }
                    if (!this.agent.mobile) {
                        return layer.msg('手机号不能为空')
                    }
                    if (!this.agent.password) {
                        return layer.msg('密码不能为空')
                    }

                    axios.post("/agent/register", this.agent)
                        .then(function (response) {
                                if (response.data.status) {
                                    layer.msg(response.data.msg);
                                }
                            }
                        );
                }
            }
        });
    </script>

@endsection

