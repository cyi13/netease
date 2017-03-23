<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CrawlersInterface;
use Illuminate\Http\Request;

class CrawlersController extends Controller
{   
    public function __construct(){

    }

    public function cloudMusicMessage(){
        
        return view('crawlers/index',array('title'=>'网易云音乐'));
    }
}
