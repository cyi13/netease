<?php
namespace App\Repositories\Common;

class Proxy extends Common {
    //抓取到代理网站地址
    protected $address = array('http://www.kuaidaili.com/proxylist/');
    //头部信息
    protected $header  = array('Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                                'Accept-Encoding:gzip, deflate, sdch',
                                'Accept-Language:zh-CN,zh;q=0.8',
                                'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.110 Safari/537.36');
    //V8js类
    protected $V8js;

    /**
     * 初始化信息
     */
    public function __construct(){
        parent::__construct();
        $this->V8js = new \V8Js();
    }

    /**
     *
     */
    public  function getProxyList(){
        //分页从1开始
        $i   = 1;
        while(true){
            $url            = $this->address[0].$i;
            $msg            = $this->kuaiDaiLiCookieRule($url);
            //匹配出代理地址
            $rule           = '|<td data-title="IP">(.*)<\/td>[\s\S]*?<td data-title="PORT">(.*)<\/td>|';
            $res            = $this->pregMathAll($rule,$msg);
            $num            = rand(1,100);
            $this->putIntoFile("crawler/proxy{$i}.txt",$msg);
            print_r($res);
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
            }else{
                break;
            }

            $i++;
        }
    }

    /**
     * 使用v8js扩展解析快代理到加密
     *
     * @param array $paramsArray
     * @return mixed
     */
    public function kuaiDaiLiCookieRule($url){
        $msg            = $this->sendCurl($url,array(),'GET',$this->header,20,array(),1);
        $rule           = '|setTimeout\(\"(.*?)\"[\s\S]*?\;(.*)\;eval|';
        $paramsArray    = $this->pregMathAll($rule,$msg['msg']);
        //拼接出要执行到js
        $string         = $paramsArray[0][0].';'.$paramsArray[1][0].';return po;}';
        $res            = $this->V8js->executeString($string);
        //匹配cookie
        $rule           = '|\'(.*)\;|';
        $res            = $this->pregMathAll($rule,$res);
        $cookieArray    = explode(';',$res[0][0]);
        //添加cookie到头部
        $this->header[] = 'Cookie:'.$cookieArray[0];
        //开始抓取
        $msg            = $this->sendCurl($url,array(),'GET',$this->header,20,array(),1);
        //去掉cookie
        array_pop($this->header);
        //检测状态码
        if($msg['httpCode'] == '521'){
            sleep(1);
            $msg['msg'] = $this->kuaiDaiLiCookieRule($url);
        }
        return $msg['msg'];
    }

    /**
     * 检测代理是否可用
     *
     * @param array $array
     */
    public function checkProxy($array=array()){
        $startTime  = time();
        $url        = 'http://www.163.com/';
        $res        = $this->sendCurl($url,array(),'GET',array(),5,$array,0);
        $endTime    = time();
        if($res['httpCode'] == '200'){
            if($endTime - $startTime < 1){
            $this->log($endTime-$startTime);
            $this->putIntoFile('crawler/proxyCanUse.txt',json_encode($array)."\r\n",FILE_APPEND);
            }
        }
    }

    /**
     * curl方式获取目标地址的内容
     *
     * 这里要判断一下状态码 只能重写一个
     *
     * @param string  $url
     * @param string  $postType 请求方式
     * @param array   $postData 附带的参数
     * @param interge $timeout  超时时间
     * @return array
     */
    protected function sendCurl($url=null,$postData=array(),$postType='GET',$header=array(),$timeout=20,$proxy = array(),$gzip=0){

        if(empty($url)) {
            return 'url is empty';
        }

        $ch = curl_init();
        //get有参数的话拼接参数
        if($postType == 'GET' & !empty($postData)){
            $dataString = http_build_query($postData);
            $url        = $url.'?'.$dataString;
        }

        //post方式 尽量使用http_build_query对数据进行处理再传输
        if($postType == 'POST'){
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($postData));
        }
        //头部信息
        if(!empty($header)){
            curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
        }
        //使用代理
        if(!empty($proxy)){
            if(array_key_exists('proxy',$proxy) & array_key_exists('proxyPort',$proxy)){
                curl_setopt($ch,CURLOPT_PROXY,$proxy['proxy']);
                curl_setopt($ch,CURLOPT_PROXYPORT,$proxy['proxyPort']);
            }
        }
        if($gzip){
            curl_setopt($ch, CURLOPT_ENCODING, "");
        }
        //请求的地址
        curl_setopt($ch,CURLOPT_URL,$url);
        //返回字符串
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //不返回 HTTP头部信息
        curl_setopt($ch,CURLOPT_HEADER,false);
        //请求超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
        // curl_setopt($ch, CURLOPT_ENCODING, 'application/json');
        //执行
        $result = curl_exec($ch);
        //获取状态码
        $httpCode  = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        //关闭
        curl_close($ch);
        //返回内容
        return array('httpCode'=>$httpCode,'msg'=>$result);
    }
}
