<?php
namespace App\Repositories\Implement;
use App\Repositories\Interfaces\CrawlersInterface;
use App\Repositories\Common;

class CrawlersRepository extends Common implements CrawlersInterface{

    public function getMusicList(){
        $url = "http://music.163.com/discover/playlist/?order=hot";
        //获得歌单类型列表
        $MusicList = $this->sendCurl($url);
        $rule = '/<dt><i class="u-icn [/s/S]*"></i>[/s/S]*</dt>/';
        preg_match($rule,$MusicList,$list);
        print_r($list);
    }
}
