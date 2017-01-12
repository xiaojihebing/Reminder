@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">修改任务</div>
                <div class="panel-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>修改失败</strong> 输入不符合要求<br><br>
                            {!! implode('<br>', $errors->all()) !!}
                        </div>
                    @endif

                    <form action="{{ URL('admin/rfq/'.$tasks->id) }}" method="POST" class="form-horizontal" role="form">
                        <input name="_method" type="hidden" value="PUT">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">任务名称：</label>
                            <div class="col-sm-10">
                                <input type="text" name="taskname" class="form-control" value="{{ $tasks->taskname }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">关键词：</label>
                            <div class="col-sm-10">
                                <input type="text" name="keyword" class="form-control" value="{{ $tasks->keyword }}">
                            </div>
                        </div>
                        @if ($tasks->type === 0)
                        <div class="form-group">
                            <label class="col-sm-2 control-label">类目ID：</label>
                            <div class="col-sm-10">
                                <input type="text" name="category" class="form-control" value="{{ $tasks->category }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">提醒邮箱：</label>
                            <div class="col-sm-10">
                                <input type="text" name="email" class="form-control" value="{{ $tasks->email }}">
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="col-sm-2 control-label">时间间隔(s)：</label>
                            <div class="col-sm-10">
                                <input type="text" name="rate" class="form-control" value="{{ $tasks->rate }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">运行状态：</label>
                            @if ($tasks->status === 0)
                            <label class="checkbox-inline">
                                <input type="radio" name="status" id="inlineRadio1" value="1">开启
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="status" id="inlineRadio2" value="0" checked>关闭
                            </label>
                            @else
                            <label class="checkbox-inline">
                                <input type="radio" name="status" id="inlineRadio1" value="1" checked>开启
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="status" id="inlineRadio2" value="0">关闭
                            </label>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button class="btn btn-info">编辑任务</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection