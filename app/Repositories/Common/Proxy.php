<?php
namespace App\Repositories\Common;

class Proxy extends Common {
    protected $xicidailiAddress = array('http://www.xicidaili.com/nn/','http://www.kuaidaili.com/proxylist/');
    protected $header = ['Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Encoding:gzip, deflate, sdch',
                        'Accept-Language:zh-CN,zh;q=0.8',
                        'Connection:keep-alive',
                        'Content-Type:application/x-www-form-urlencoded',
                        'Host:www.kuaidaili.com',
                        'Cookie:_ydclearance=c873c2e0a58598b0a61ac5f8-2944-4d64-ba00-91a79c080ce7-1490785921; channelid=0; sid=1490778627531557; _ga=GA1.2.423397137.1490770139; Hm_lvt_7ed65b1cc4b810e9fd37959c9bb51b31=1490770139; Hm_lpvt_7ed65b1cc4b810e9fd37959c9bb51b31=1490780680',
                        'Upgrade-Insecure-Requests:1',
                        'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36'];
//Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
//Accept-Encoding:gzip, deflate, sdch
//Accept-Language:zh-CN,zh;q=0.8
//Connection:keep-alive
//Cookie:_ydclearance=c873c2e0a58598b0a61ac5f8-2944-4d64-ba00-91a79c080ce7-1490785921; channelid=0; sid=1490778627531557; _ga=GA1.2.423397137.1490770139; Hm_lvt_7ed65b1cc4b810e9fd37959c9bb51b31=1490770139; Hm_lpvt_7ed65b1cc4b810e9fd37959c9bb51b31=1490780680
//Host:www.kuaidaili.com
//Referer:http://www.kuaidaili.com/proxylist/1/
//Upgrade-Insecure-Requests:1
//User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36
    public  function getProxyList(){
        $url = $this->xicidailiAddress[0];
        for($i=1;$i<=10;$i++){
            $url    = $url.$i;
            $msg    = $this->sendCurl($url);
            $rule   = '|<td class="country"><img src[\s\S]*?<td>(.*)<\/td>[\s\S]*?<td>(.*)<\/td>[\s\S]*?<div title="(.*?)"[\s\S]*?<div title="(.*?)"|';
            //匹配出代理地址
            $res    = $this->pregMathAll($rule,$msg);
            if(!empty($res) & is_array($res)){
                $proxyIP        = $res[0];
                $proxyPort      = $res[1];
                $proxySpeed     = $res[2];
                $proxyLinkTime  = $res[3];
                foreach ($proxyIP as $key=>$value){
                    if(!empty($proxySpeed[$key]) && !empty($proxyLinkTime[$key])){
                        //验证时间
                        if(intval($proxySpeed[$key]) < 1 && intval($proxyLinkTime[$key]) < 1){
                            //验证是否可连接
                            $array = array('proxy'=>$value,'proxyPort'=>$proxyPort[$key]);
                            $this->log(json_encode($array));
                            $this->log('start check');
                            $this->checkProxy($array);
                        }
                    }
                }
            }
        }
    }
    public function getFastProxyList(){
        $url = $this->xicidailiAddress[1];
        $i   = 1;
        while(true){
            $url = $url.$i;
            $res = $this->sendCurl($url,array(),'POST',$this->header,20,array(),1);
            $this->putIntoFile('crawler/fastProxy.txt',$res);
            die;
        }
    }

    public function checkProxy($array){
        $startTime = time();
        $url = 'https://www.baidu.com/';
        $res = $this->sendCurl($url,array(),'GET',array(),20,$array);
        $endTime = time();
        if(!empty($res)){
            $this->log($endTime-$startTime);
            $this->putIntoFile('crawler/proxyCanUse.txt',json_encode($array).'/n');
        }
    }

}
