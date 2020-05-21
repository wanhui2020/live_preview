@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">价格名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" lay-verify="required" placeholder="请输入价格名称"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">充值金额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="money" lay-verify="required|money|minus" placeholder="请输入充值金额"
                                           autocomplete="off" class="layui-input" id="money">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">兑换比例</label>
                                <div class="layui-input-block">
                                    <input type="text" name="rate" lay-verify="required|minus" placeholder="请输入兑换比例"
                                           autocomplete="off" class="layui-input" id="rate">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">能量兑换</label>
                                <div class="layui-input-block">
                                    <input type="text" lay-verify="required|minus" placeholder="" value="{{env('PLATFORM_EXCHANGE_RATE')}}"
                                           autocomplete="off" class="layui-input"  readonly>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">最终能量</label>
                                <div class="layui-input-block">
                                    <input type="text" placeholder="" autocomplete="off" class="layui-input"  id="last" >
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">价格描述</label>
                                <div class="layui-input-block">
                                    <input type="text" name="remark" placeholder="请输入价格描述" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item ">
                                <div class="layui-input-block">
                                    <input type="button" class="layui-btn" lay-submit lay-filter="formSubmit"
                                           value="确认">
                                    <input type="button" class="layui-btn layui-btn-primary " id="close" value="关闭">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script type="application/javascript">
        //计算获得的总能量
        var env ="{{env('PLATFORM_EXCHANGE_RATE')}}"
        var money=document.getElementById('money');
        var rate=document.getElementById('rate');
        var last=document.getElementById('last');
        //内容改变事件
        money.onchange=function(){
            last.value = new Number(this.value * rate.value * env).toFixed(4);
        };
        rate.onchange=function(){
            last.value =  new Number(this.value * money.value * env).toFixed(4);
        };
        last.onchange=function(){
            rate.value =  new Number(this.value / money.value / env).toFixed(4);
        };

        layui.use(['form', 'upload', 'layedit'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload
                , layedit = layui.layedit;
            //创建一个编辑器
            var editIndex = layedit.build('demo');
            //自定义验证规则
            form.verify({
                article_desc: function (value) {
                    layedit.sync(editIndex);
                }
            });
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field
                        , IllegalString = "[`~!#$^&*()=|{}':;',\\[\\].<>/?~！#￥……&*（）——|{}【】‘；：”“']‘'"
                        , name = field.name
                        , index_name = name.length - 1
                        , sname = name.charAt(index_name);
                    if (IllegalString.indexOf(sname) >= 0) {
                        layer.alert("公告名称不能是特殊字符", {icon: 2});
                        return false;
                    }
                    axios.post("{{url('system/platform/price/store')}}", field)
                        .then(function (response) {
                            if (response.data.status) {
                                parent.layer.msg(response.data.msg);
                                return parent.layer.close(index);
                            }
                            return layer.msg(response.data.msg);
                        });
                }
            );
            form.verify({ // 验证金额不能为负
                moneyMinus: function (value, item) {
                    if (value < 0) {
                        return '金额不能为负';
                    }
                },
                minus: function (value, item) {
                    if (value < 0) {
                        return '不能为负';
                    }
                },
                Numbers: function (value, item) {
                    let role = /^(?:(?:(?:[1-9]\d{0,2}(?:,\d{3})*)|[1-9]\d*|0))?$/;
                    if (!role.test(value)) {
                        return '请输入正确的数字，不能为负或小数。';
                    }
                }
            });
        });
    </script>
@stop
