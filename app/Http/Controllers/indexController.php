<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controlle;

class indexController extends Controller{

	public function __construct(){
		view()->share('netTitle','FUNCTION');
	}

	/**
	 * 项目首页
	 */
	public function index(){
		return view('index/index',array('title'=>'主页'));
	}
}
