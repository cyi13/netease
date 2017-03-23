<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controlle;
use Illuminate\Support\Facades\DB;
class indexController extends Controller{

	/**
	 * 项目首页
	 */
	public function index(){
		
	    //查找出所有的function信息
	    // $list = \App\Models\FunctionList::select('id','functionName','functionAddress')->get();
		$list = array();
	    return view('index/index',array('title'=>'主页','list'=>$list));
	}
}
