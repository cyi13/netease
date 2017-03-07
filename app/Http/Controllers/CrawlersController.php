<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CrawlersInterface;
use Illuminate\Http\Request;

class CrawlersController extends Controller
{
    public function __construct(CrawlersInterface $crawlers){
        $this->craws = $crawlers;
    }
    
    public function cloudMusic(){
        $res = $this->craws->getMusicList();
        print_r($res);
    }
}
