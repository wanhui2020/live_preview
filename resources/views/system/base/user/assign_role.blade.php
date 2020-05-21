@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData">
                            <input type="hidden" name="id">
                            <div class="layui-form-item">
                                <label class="layui-form-label">用户</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" placeholder="" autocomplete="off" class="layui-input" disabled>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">角色</label>
                                <div class="layui-input-block">
                                    @php
                                        $roleIds = $user->roles->pluck('id')->toArray();
                                    @endphp
                                    @foreach($roles as $role)
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" title="{{ $role->name }}"
                                               @if(in_array($role->id,$roleIds)) checked @endif
                                        >
                                    @endforeach
                                </div>
                            </div>
                            <div class="layui-form-item ">
                                <div class="layui-input-block">
                                    <input type="button" class="layui-btn" lay-submit lay-filter="formSubmit" value="确认">
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
        layui.use(['form', 'upload'], function () {
            let $ = layui.$
                , form = layui.form;
            form.val("formData",{!! $user !!});

            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;

                    axios.post("{{url('system/base/user/assign_role')}}", field)
                        .then(function (response) {
                            if (response.data.status) {
                                parent.layer.msg(response.data.msg);
                                return parent.layer.close(index);
                            }
                            return layer.msg(response.data.msg);
                        });
                    let index = parent.layer.getFrameIndex(window.name);
                }
            );
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
        });

    </script>
@endsection
