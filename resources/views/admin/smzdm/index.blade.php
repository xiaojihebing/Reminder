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

                    <a href="{{ url('admin/smzdm/create') }}" class="btn btn-lg btn-primary">添加新任务</a>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>任务名</th>
                          <th>查询类型</th>
                          <th>间隔</th>
                          <th>状态</th>
                          <th>上次查询时间</th>
                          <th>操作</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($tasks as $task)
                        <tr>
                          <td>{{ $task->id }}</td>
                          <td>{{ $task->name }}</td>
                          @if($task->type === 0)
                          <td>品牌</td>
                          @else
                          <td>类目</td>
                          @endif
                          <td>{{ $task->rate }}</td>
                          @if($task->status === 1)
                          <td>运行中</td>
                          @else
                          <td>已停止</td>
                          @endif
                          <td>{{ $task->updated_at }}</td>
                          <td>
                            <a href="{{ url('admin/smzdm/'.$task->id.'/edit') }}" class="btn btn-success">编辑</a>
                            <form action="{{ url('admin/smzdm/'.$task->id) }}" method="POST" style="display: inline;">
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