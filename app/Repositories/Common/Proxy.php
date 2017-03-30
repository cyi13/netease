<?php
namespace App\Repositories\Common;

class Proxy extends Common {
    //抓取到代理网站地址
    protected $address = array('http://www.kuaidaili.com/proxylist/');
    //快代理网站请求头部


    public  function getProxyList(){
        $url = $this->address[0];
        $i   = 1;
        $this->kuaiDaiLiCookieRule(array());die;
        while(true){
            $url    = $url.$i;
            $start = time();
            $msg    = $this->sendCurl($url,array(),'GET',array(),20,array(),1);
            $num    = rand(1,100);
            echo time()-$start;
            $this->putIntoFile("crawler/fastProxy{$num}.html",$msg);
            $rule = '|[\s\S]*setTimeout\("[\s\S]*?\((.*?)\)[\s\S]*?\[(.*?)\][\s\S]*?"qo=(.*?);[\s\S]*?\>\>'
                    .'(.*?)\)[\s\S]*?\)\-(.*?)\)[\s\S]*?=\s(.*?);[\s\S]*?\+\s(.*?)\)[\s\S]*?\+\s(.*?)\)[\s\S]*?'
                    .'\<\<\s(.*?)\)[\s\S]*?%\s(.*?)\)|';
            $paramsArray = $this->pregMathAll($rule,$msg);
            $ss = $this->kuaiDaiLiCookieRule($paramsArray);
            echo $ss;die;
            //匹配出代理地址
            $rule   = '|<td data-title="IP">(.*)<\/td>[\s\S]*?<td data-title="PORT">(.*)<\/td>|';
            $res    = $this->pregMathAll($rule,$msg);
            if(!empty($res) & is_array($res)){
                if(is_array($res[0])){
                    $proxyIP        = $res[0];
                    $proxyPort      = $res[1];
                    foreach ($proxyIP as $key=>$value){
                        //验证是否可连接
                        $array = array('proxy'=>$value,'proxyPort'=>$proxyPort[$key]);
                        $this->checkProxy($array);
                    }
                }
                break;
            }
            $i++;
        }
    }

    //快代理的cookie生成规则
    public function kuaiDaiLiCookieRule($paramsArray=array()){
        if(!empty($paramsArray)){
            $this->putIntoFile('crawler/kuaidailiData.txt',json_encode($paramsArray));die;
        }
        $paramsArray = json_decode($this->getFile('crawler/kuaidailiData.txt'));
        $oo = explode(',',$paramsArray[1][0]);
        array_walk($oo,function(&$item){
            $item = hexdec($item);
        });;
        unset($paramsArray[1]);
        array_walk($paramsArray,function(&$item){
            $item[0] = intval($item[0]);
        });
        $qo = $paramsArray[2][0];
        do{
            $oo[$qo] = (-$oo[$qo])&0xff;
            $oo[$qo] = ((($oo[$qo]>>5)|(($oo[$qo]<<3)&0xff))-209)&0xff;
        }while(--$qo>=2);
        $qo = $paramsArray[5][0];
        do{
            $oo[$qo] = ($oo[$qo] - $oo[$qo-1]) & 0xff;
        }while(--$qo >= 3);
        $qo = 1;
        for(;;){
            if ($qo > $paramsArray[5][0]){
                break;
            }
            $oo[$qo] = (((((($oo[$qo] + $paramsArray[6][0]) & 0xff) + $paramsArray[7][0]) & 0xff) << $paramsArray[8][0]) & 0xff)
                     | ((((($oo[$qo] + $paramsArray[6][0]) & 0xff) + $paramsArray[7][0]) & 0xff) >> (8-$paramsArray[8][0]));
            $qo++;
        }
        $po = "";
        $length = count($oo);
        for ($i = 1; $i < $length - 1; $i++){
            if ($i % $paramsArray[9][0]){
                $po .= chr($oo[$i] ^ $paramsArray[0][0]);
                echo $oo[$i] ^ $paramsArray[0][0];
                echo '--';
            }
        }
        return $po;
    }
    public function checkProxy($array=array()){
        $startTime = time();
        $url = 'https://www.baidu.com/';
        $res = $this->sendCurl($url,array(),'GET',array(),20,$array);
        $endTime = time();
        if(!empty($res)){
            if($endTime - $startTime < 1){
            $this->log($endTime-$startTime);
            $this->putIntoFile('crawler/proxyCanUse.txt',json_encode($array).'/n');
            }
        }
    }
}
