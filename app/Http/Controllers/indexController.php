<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controlle;

class indexController extends Controller{

	/**
	 * 项目首页
	 */
	public function index(){
		return view('index/index');
	}
}
