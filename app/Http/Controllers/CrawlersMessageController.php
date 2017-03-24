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
        $this->crawlerMsg = $crawlerMeg;
    }

    public function cloudMusicMessage(){
        //获取歌曲列表
        $list = $this->crawlerMsg->getMusicListMessage();
        if(!empty($list)){
            //处理一下歌手信息
            foreach($list as $value){
                $value->singerMessage = json_decode($value->singerMessage);
            }
        }
        $viewData = array('title'=>'网易云音乐','musicList'=>$list);
        return view('crawlers/index',$viewData);
    }
}
