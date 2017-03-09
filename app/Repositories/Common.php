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
    protected function pregMathAll($rule,$string){

        if(empty($rule) && empty($string)){
            return null;
        }
        
        preg_match_all($rule, $string, $list);
        //使用第二个结果
        if(!empty($list[1])){
            array_shift($list);
            return $list;
        }else{
            return null;
        }
    }

    /**
     * 信息写入文件中 
     * @return bollean 
     */
    protected function putIntoFile($filePath='',$msg=''){

        if(empty($filePath) && empty($msg)){
            return false;
        }
        //文件放到 根目录storage下面
        $fileRootPath = storage_path('app\public');

        $fileMsg = explode('/', $filePath);
        //最后一个为文件名
        $fileName = array_pop($fileMsg);
        //判断目录是否存在 否则建立目录
        $currentPath = $fileRootPath.'\\';
        foreach ($fileMsg as $key => $value) {
            $currentPath .= $value.'\\';
            if(!is_dir($currentPath)){
                mkdir($currentPath,777);
            }
        }

        //开始推送数据进去 成功返回的是写入的字节数 失败则返回false
        $res = file_put_contents($currentPath.$fileName,$msg);

        return $res;
    }

    /**
     * 
     */
    protected function getFile($filePath=''){

        if(empty($filePath)){
            return false;
        }
        //文件存放根目录
        $fileRootPath = storage_path('app\public');
        //使用反斜杠
        $filePath = str_replace('/', '\\', $filePath);

        $msg = file_get_contents($fileRootPath.'\\'.$filePath);

        return $msg;
    }

    /**
     * 转换字符串中的汉子为阿拉伯数字 暂时匹配一个万
     * @param  [type] $string [description]
     * @return [type]         [description]
     */
    protected function chineseToNumber($string=''){
        if(empty($string)){
            return false;
        }
        //对照表
        $array = array('万'=>'0000');

        //正则匹配分离中英文   
        //http://www.cnblogs.com/toumingbai/p/4688433.html  百度找的 php匹配中文和js匹配中文不一样
        $rule = '|([0-9]*)([\x{4e00}-\x{9fa5}])|u';
        $res = $this->pregMathAll($rule,$string);
        if(!empty($res)){
            $number  = $res[0][0];
            $chinese = $res[1][0];
            if(array_key_exists($chinese,$array)){
                $number = $number.$array[$chinese];
                return intval($number);
            }
        }
        return $string;
    }
}