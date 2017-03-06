<?php
namespace App\Repositories;
class Common {

    /**
     * curl方式获取目标地址的内容
     *
     * @param string  $url
     * @param string  $postType 请求方式
     * @param array   $postData 附带的参数
     * @param interge $timeout  超时时间
     * @return array
     */
    protected function sendCurl($url=null,$postType='GET',$postData=array(),$timeout=10){
        if(empty($url)) {
            return 'url is empty';
        }
        $ch = curl_init();
        //get有参数的话拼接参数
        if($postType == 'GET' & !empty($postData)){
            $dataString = http_build_query($postData);
            $url        = $url.'?'.$dataString;
        }

        curl_setopt($ch,CURLOPT_URL,$url);

        //数据传输方式
        if($postType == 'POST'){
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        }

        //返回字符串
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //不返回 HTTP头部信息
        curl_setopt($ch,CURLOPT_HEADER,false);
        //请求超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
        //执行
        $result = curl_exec($ch);
        //关闭
        curl_close($ch);
        //返回内容
        return $result;
        // return !empty($result) ? htmlspecialchars($result) : null;
    }

    /**
     * 正则匹配
     * @param  string $rule   正则表达式
     * @param  string $string 字符串内容
     * @return array          有匹配返回一个结果数组 否则返回空
     */
    public function pregMathAll($rule,$string){

        if(empty($rule) && empty($string)){
            return null;
        }

        preg_match_all($rule, $string, $list);
        //使用第二个结果
        if(!empty($list[1])){
            unset($list[0]);
            return $list;
        }else{
            return null;
        }

    }
}