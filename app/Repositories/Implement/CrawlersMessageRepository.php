<?php

namespace App\Repositories\Implement;
use App\Repositories\Common\Common;
use App\Repositories\Interfaces\CrawlersMessageInterface;

class CrawlersMessageRepository extends Common Implements CrawlersMessageInterface{
    
    public function getMusicListMessage(){
        $MusicModel = $this->getModel('CloudMusicMessage');
        $list = $MusicModel->getMusicListMessage();
        return $list;
    }
}