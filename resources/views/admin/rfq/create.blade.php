@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">添加任务</div>
                <div class="panel-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>新增失败</strong> 输入不符合要求<br><br>
                            {!! implode('<br>', $errors->all()) !!}
                        </div>
                    @endif

                    <form action="{{ url('admin/rfq') }}" method="POST">
                        {!! csrf_field() !!}
                        <input type="text" name="taskname" class="form-control" required="required" placeholder="任务名称">
                        <br>
                        <input type="text" name="keyword" class="form-control" required="required" placeholder="关键词">
                        <br>
                        <input type="text" name="category" class="form-control" placeholder="分类ID">
                        <br>
                        <input type="text" name="email" class="form-control" required="required" placeholder="提醒邮箱">
                        <br>
                        <input type="text" name="rate" class="form-control" required="required" placeholder="间隔时间(s)">
                        <br>
                        <!-- <input type="text" name="times" class="form-control" placeholder="已查询次数">
                        <br> -->
                        <button class="btn btn-lg btn-info">新增产品</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection