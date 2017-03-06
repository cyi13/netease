<?php
namespace App\Repositories\Implement;
use App\Repositories\Interfaces\CrawlersInterface;
use App\Repositories\Common;

class CrawlersRepository extends Common implements CrawlersInterface{

    public function getMusicList(){
       
        //获得歌单类型列表
        if(file_exists('index.txt')){
        	$MusicList = file_get_contents('index.txt');
        }else{
        	$url = "http://music.163.com/discover/playlist/?order=hot";
        	$MusicList = $this->sendCurl($url);
        }
        // echo '<pre>';print_r(htmlspecialchars($MusicList));die;
        //歌单的所属类型
        // $rule = '|<dt><i class="u-icn u-[\s\S].*"></i>([\s\S].*)</dt>|';
        // $rule = '|<dl class="f-cb">[\s\S].*<dt><i class="u-icn [\s\S].*"></i>([\s\S].*)</dt></dl>([\s\S].*)</dd>|';
        $rule = '|<dl class="f-cb">[\s\S].*</i>([\s\S].*)</dt>([\s\S].*)</dd>|';
        $list = $this->pregMathAll($rule,$MusicList);
        print_r($list);die;
        $rule = '|<a class="s-fc1 " href="([\s\S].*)" data-cat="([\s\S].*)">[\s\S].*</a>|';
        $array = $this->pregMathAll($rule,$MusicList);


    }
}
