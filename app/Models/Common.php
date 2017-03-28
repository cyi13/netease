<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 数据获取的公共方法
 */
class Common extends Model{
    
    protected $Redis;

    /**
     * 连接Redis
     *
     * 这是php原生的连接方式
     * Reids配置信息在.env里面设置
     * laravel Facades 一直提示错误，还没找到解决方式
     *
     * @return object
     */
    protected function Redis(){

        if(empty($this->Redis)){
            $this->Redis = new \redis();
            //redis配置信息
            $redisConfig = config('database.redis');
            $host = $redisConfig['default']['host'];
            $port = $redisConfig['default']['port'];
            $this->Redis->Connect($host,$port);
        }
        return $this->Redis;
    }
}