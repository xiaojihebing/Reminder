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

                    <form action="{{ url('admin/jingdong') }}" method="POST">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="radio-inline">
                                <input type="radio" name="type" id="inlineRadio1" value="0" checked> 降价
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type" id="inlineRadio2" value="1"> 库存
                            </label>
                        </div>
                        <input type="text" name="name" class="form-control" required="required" placeholder="任务名称">
                        <br>
                        <input type="text" name="skuid" class="form-control" required="required" placeholder="商品SKUID">
                        <br>
                        <input type="text" name="current_price" class="form-control" placeholder="当前价格">
                        <br>
                        <input type="text" name="target_price" class="form-control" placeholder="目标价格">
                        <br>
                        <input type="text" name="rate" class="form-control" required="required" placeholder="间隔时间(s)">
                        <br>
                        <input type="text" name="times" class="form-control" placeholder="已查询次数">
                        <br>
                        <input type="text" name="email" class="form-control" required="required" placeholder="提醒邮箱">
                        <br>
                        <input type="text" name="phone" class="form-control" required="required" placeholder="提醒手机号">
                        <br>
                        <button class="btn btn-lg btn-info">新增任务</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection