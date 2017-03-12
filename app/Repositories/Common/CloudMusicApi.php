<?php
namespace App\Repositories\Common;

use App\Repositories\Common\Common;

/**
 * 网易云音乐的API
 *
 * api的参数加密参考 https://github.com/axhello/NeteaseCloudMusicApi和知乎https://www.zhihu.com/question/36081767
 * @author CGY 
 */
class CloudMusicApi extends Common{

	 /**
     * 网易云的域名地址
     * @var string
     */
    const COLUDDMIAN    = 'http://music.163.com';

    const MODULUS       = '00e0b509f6259df8642dbc35662901477df22677ec152b5ff68ace615bb7b725152b3ab17a876aea8a5aa76d2e417629ec4ee341f56135fccf695280104e0312ecbda92557c93870114af6c9d05c4f7f0c3685b7a46bee255932575cce10b424d813cfe4875d3e82047b97ddef52741d546b8e289dc6935b3ece0462db0a22b8e7';

    const NONCE         = '0CoJUm6Qyw8W8jud';

    const PUBKEY        = '010001';
    
    /**
     * 请求的头部信息
     */
    protected $header = ['Accept:*/*','Accept-Encoding:application/json','Accept-Language:zh-CN,zh;q=0.8','Connection:keep-alive',
                        'Content-Type:application/x-www-form-urlencoded','Host:music.163.com','Origin:http://music.163.com',
                        'Referer:http://music.163.com/song?id=422132597','User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36'];

    /**
     * 网易云音乐歌曲评论数
     * @param  integer  $musicId 歌曲id
     * @param  integer  $offset  
     * @param  integer  $limit   
     * @return string
     */
    public function musicCommentMsg($musicId,$offset=0,$limit=20){
    	//需要指定歌曲id
    	$url = "http://music.163.com/weapi/v1/resource/comments/R_SO_4_{$musicId}?csrf_token=";
    	//参数构建
    	$musicParam = json_encode(array('rid'=>"R_SO_4_{$musicId}",'offset'=>$offset,'total'=>'true','limit'=>$limit,'csrf_token'=>''));
    	//参数加密
    	$params = $this->prepare(array('params'=>$musicParam));
    	//开始请求
    	return $this->sendCurl($url,$params,'POST',$this->header);
    }

    public function musicMessage($musicId){
    	//需要指定歌曲Id
    	$url = 'http://music.163.com/song?id='.$musicId;
    	//不需要参数加密就可以抓取
    	return $this->sendCurl($url);
    }
    /**
     * 参数加密
     * @param  array $data 
     * @return array
     */
  	protected function prepare($data){
        $secretKey = $this->createSecretKey(16);
        $data['params'] = $this->aesEncrypt($data['params'], self::NONCE);
        $data['params'] = $this->aesEncrypt($data['params'], $secretKey);
        $data['encSecKey'] = $this->rsaEncrypt($secretKey);
        return $data;
    }
    
    /**
     * aes加密
     * @param  [type] $secretData [description]
     * @param  [type] $secret     [description]
     * @return [type]             [description]
     */
    protected function aesEncrypt($secretData,$secret){
        $vi = '0102030405060708';
        return openssl_encrypt($secretData,'aes-128-cbc',$secret,false, $vi);
    }

    /**
     * rsa加密
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
    protected function rsaEncrypt($text){
        $rtext      = strrev(utf8_encode($text));
        $keytext    = $this->bchexdec($this->strToHex($rtext));
        $biText     = new Math_BigInteger($keytext);
        $biKey      = new Math_BigInteger($this->bchexdec(self::PUBKEY));
        $biMod      = new Math_BigInteger($this->bchexdec(self::MODULUS));
        $key        = $biText->modPow($biKey, $biMod)->toHex();
        return str_pad($key, 256, '0', STR_PAD_LEFT);
    }


    protected function bchexdec($hex){
        $dec = 0;
        $len = strlen($hex);

        for ($i = 0; $i < $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i])), bcpow('16', strval($len - $i - 1))));
        }

        return $dec;
    }

    protected function strToHex($str){
        $hex = '';

        for ($i = 0; $i < strlen($str); $i++) {
            $hex .= dechex(ord($str[$i]));
        }

        return $hex;
    }


}