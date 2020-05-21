@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="formData">
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                            <div class="layui-form-item">
                                <label class="layui-form-label">角色名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" lay-verify="required" placeholder="" autocomplete="off"
                                           class="layui-input" value="{{ $role->name }}" disabled>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">权限</label>
                                <div class="layui-input-block">
                                    @php
                                        $permissionIds = $role->permissions->pluck('id')->toArray()
                                    @endphp
                                    @foreach($permissions as $permission)
                                        <input type="checkbox" name="permission[]" value="{{ $permission->id }}" title="{{ $permission->name }}"
                                        @if(in_array($permission->id,$permissionIds)) checked @endif
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
    <script type="text/javascript">
        layui.use(['form'], function () {
            let $ = layui.$
                , form = layui.form;
            form.val("formData",{!! $role !!});
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;

                    axios.patch("/system/base/role/" + field.role_id + "/assign_permission", field)
                        .then(function (response) {
                            if (response.data.status) {
                                parent.layer.msg(response.data.msg);
                                let index = parent.layer.getFrameIndex(window.name);
                                return parent.layer.close(index);
                            }
                            return layer.msg(response.data.msg);
                        });
                }
            );
            // let index = parent.layer.getFrameIndex(window.name);
            // $(document).on('click', '#close', function () {
            //     parent.layer.close(index);
            // });
            // parent.layer.iframeAuto(index);

        });

    </script>
@endsection
