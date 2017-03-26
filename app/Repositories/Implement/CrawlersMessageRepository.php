<?php

namespace App\Repositories\Implement;
use App\Repositories\Common\Common;
use App\Repositories\Interfaces\CrawlersMessageInterface;

class CrawlersMessageRepository extends Common Implements CrawlersMessageInterface{
    
    protected $limit = 15;
    
    public function getMusicListMessage($pageNum=1){
        $offset = $this->limit * ceil(intval($pageNum)-1);
        $MusicModel = $this->getModel('CloudMusicMessage');
        $list       = $MusicModel->getMusicListMessage($offset);
        if(!empty($list)){
            //处理一下歌手信息
            foreach($list as $value){
                $value->singerMessage = json_decode($value->singerMessage);
            }
        }
        $this->page($MusicModel);
        return $list;
    }
    
    /**
    * 分页数据渲染
    */
    public function page($model,$where=array()){
        $totalCount = $model->getTotalCount();
        if($totalCount){
            $totalPageNum = ceil($totalCount/$this->limit);
            view()->share('totalPageNum',$totalPageNum);
        }
    }
    
}