<?php
namespace App\Repositories\Common;

class Proxy extends Common {
    //抓取到代理网站地址
    protected $address = array('http://www.kuaidaili.com/proxylist/');
    //快代理网站请求头部
    protected $header = ['Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
'Accept-Encoding:gzip, deflate, sdch',
'Accept-Language:zh-CN,zh;q=0.8',
'Cache-Control:max-age=0',
'Connection:keep-alive',
'_ydclearance=a0517a508a8c84fac6152f25-9be1-486e-84e0-afab6ceb65e8-1490804080;',
'Host:www.kuaidaili.com',
'Referer:http://www.kuaidaili.com/proxylist/',
'Upgrade-Insecure-Requests:1',
'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36'];

    public  function getProxyList(){
        $url = $this->address[0];
        $i   = 1;
        while(true){
            $url    = $url.'2';
            $msg    = $this->sendCurl($url,array(),'GET',$this->header,20,array(),1);
            $this->putIntoFile('crawler/fastProxy.txt',$msg);die;
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

    public function checkProxy($array){
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
