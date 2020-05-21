@extends('layouts.base')
@section('content')
    <div class="layui-fluid">

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        搜索会员结果
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <div class="layui-form">
                            <table class="layui-table">
                                <colgroup>
                                    <col width="120">
                                    <col width="150">
                                    <col width="80">
                                    <col width="80">
                                    <col>
                                    <col width="200">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>昵称</th>
                                    <th>性别</th>
                                    <th>年龄</th>
                                    <th>位置</th>
                                    <th>时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($members as $item)
                                    <tr>
                                        <td>{{$item->no}}</td>
                                        <td>{{$item->nick_name}}</td>
                                        <td>{{$item->sex==0?'男':'女'}}</td>
                                        <td>{{$item->age}}</td>
                                        <td>{{$item->address}}</td>
                                        <td>{{$item->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection

