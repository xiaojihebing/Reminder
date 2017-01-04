@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">文章管理</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {!! implode('<br>', $errors->all()) !!}
                        </div>
                    @endif

                    <a href="{{ url('admin/task/create') }}" class="btn btn-lg btn-primary">添加新任务</a>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>任务名</th>
                          <th>关键词</th>
                          <th>间隔</th>
                          <th>上次查询时间</th>
                          <th>操作</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($tasks as $task)
                        <tr>
                          <td>{{ $task->id }}</td>
                          <td>{{ $task->taskname }}</td>
                          <td>{{ $task->keyword }}</td>
                          <td>{{ $task->rate }}</td>
                          <td>{{ $task->updated_at }}</td>
                          <td>
                            <a href="{{ url('admin/task/'.$task->id.'/edit') }}" class="btn btn-success">编辑</a>
                            <form action="{{ url('admin/task/'.$task->id) }}" method="POST" style="display: inline;">
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