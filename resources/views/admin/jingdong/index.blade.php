@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">任务管理</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {!! implode('<br>', $errors->all()) !!}
                        </div>
                    @endif

                    <a href="{{ url('admin/jingdong/create') }}" class="btn btn-lg btn-primary">添加新任务</a>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>名称</th>
                          <th>SKUID</th>
                          <th>提醒类型</th>
                          <th>间隔(s)</th>
                          <th>状态</th>
                          <th>上次查询</th>
                          <th>操作</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($jingdong as $jd)
                        <tr>
                          <td>{{ $jd->id }}</td>
                          <td>{{ $jd->name }}</td>
                          <td>{{ $jd->skuid }}</td>
                          @if ($jd->type === 0) <td>降价</td>
                          @else <td>库存</td>
                          @endif
                          <td>{{ $jd->rate }}</td>
                          @if ($jd->status === 0) <td>已停止</td>
                          @else <td>运行中</td>
                          @endif
                          <td>{{ $jd->updated_at }}</td>
                          <td>
                            <a href="{{ url('admin/jingdong/'.$jd->id.'/edit') }}" class="btn btn-success">编辑</a>
                            <form action="{{ url('admin/jingdong/'.$jd->id) }}" method="POST" style="display: inline;">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger">删除</button>
                        </form></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection