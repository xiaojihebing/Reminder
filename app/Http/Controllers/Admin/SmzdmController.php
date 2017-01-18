<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Smzdmtask;
use Redirect, Auth;

class SmzdmController extends Controller
{
    //首页
    public function index()
	{
    	return view('admin/smzdm/index')->withtasks(Smzdmtask::all());
	}
	
	//新增
    public function create()
	{
    	return view('admin/smzdm/create');
	}

	//存储
	public function store(Request $request)
	{
		$this->validate($request, [
    		'name' => 'required',
    		'rurl' => 'required',
    		'email' => 'required',
    		'rate' => 'required',
    		'type' => 'required'
    	]);

    	$task = new Smzdmtask;
    	$task->type = $request->get('type');
    	$task->name = $request->get('name');
    	$task->rurl = $request->get('rurl');
    	$task->email = $request->get('email');
    	$task->phone = $request->get('phone');
    	$task->rate = $request->get('rate');
    	$task->status = 1;

    	if ($task->save()) {
        	return redirect('admin/smzdm');
    	} else {
        	return redirect()->back()->withInput()->withErrors('保存失败！');
    	}
	}

	//编辑
	public function edit($id)
	{
		return view('admin/smzdm/edit')->withTask(Smzdmtask::find($id));
	}

	//更新
	public function update(Request $request,$id)
	{
        $this->validate($request, [
            'name' => 'required',
            'rurl' => 'required',
            'rate' => 'required',
            'email' => 'required',
        ]);

        $jd = Smzdmtask::find($id);
        $jd->name = $request->get('name');
        $jd->rurl = $request->get('rurl');
        $jd->type = $request->get('type');
        $jd->rate = $request->get('rate');
        $jd->email = $request->get('email');
        $jd->phone = $request->get('phone');
        $jd->status = $request->get('status');
        // 保存
        if ($jd->save()) {
            return Redirect::to('admin/smzdm');
        } else {
            return Redirect::back()->withInput()->withErrors('保存失败！');
        }

	}

	// 删除
	public function destroy()
	{

	}
}
