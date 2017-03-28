<?php

namespace App\Http\Controllers;
use App\Repositories\Interfaces\CrawlersMessageInterface;
use Illuminate\Http\Request;

/**
 * 爬虫获取的数据信息显示
 * 
 * @author CGY 
 */
class CrawlersMessageController extends CommonController{   

    public function __construct(CrawlersMessageInterface $crawlerMeg){
        parent::__construct();
        view()->share('title','网易云音乐');
        $this->crawlerMsg = $crawlerMeg;
    }

    public function getCloudMusicMessage(){
        return view('crawlers/index');
    }

    public function getCloudMusicMessagePage(Request $request){
        if($request->ajax()){
            $data  = $request->all();
            $list  = $this->crawlerMsg->getMusicListMessage($data);
            return response()->json($list);
        }
    }
}
