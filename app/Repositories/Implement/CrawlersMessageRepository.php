<?php

namespace App\Repositories\Implement;
use App\Repositories\Common\Common;
use App\Repositories\Interfaces\CrawlersMessageInterface;
use App\Repositories\Common\Proxy;

class CrawlersMessageRepository extends Common Implements CrawlersMessageInterface{
    
    protected $limit = 15;
    
    public function getMusicListMessage($data=array()){
        //查询的页数
        if(!array_key_exists('pageNum',$data)){
            $data['pageNum'] = 1;
        }
        $offset = $this->limit * ceil(intval($data['pageNum'])-1);
        $where  = array();
        if(array_key_exists('name',$data) && array_key_exists('searchName',$data)){
            if(!empty($data['name']) && !empty($data['searchName'])){
                $where[] = array($data['searchName'],'like','%'.$data['name'].'%');
            }
        }
        $MusicModel = $this->getModel('CloudMusicMessage');
        $list       = $MusicModel->getMusicListMessage($offset,$where);
        if(!empty($list)){
            //处理一下歌手信息
            foreach($list as $value){
                $value->singerMessage = json_decode($value->singerMessage);
            }
        }
        $this->page($MusicModel,$where);
        return $list;
    }
    
    /**
    * 分页数据渲染
    */
    public function page($model,$where=array()){
        $totalCount = $model->getTotalCount($where);
        if($totalCount){
            $totalPageNum = ceil($totalCount/$this->limit);
            view()->share('totalPageNum',$totalPageNum);
        }
    }

    public function testProxy(){
        $Proxy = new Proxy();
        $Proxy->getFastProxyList();
    }
    
}