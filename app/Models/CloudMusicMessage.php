<?php

namespace App\Models;

class CloudMusicMessage extends Common
{
	protected $table      = 'cloud_music_message';
	protected $primaryKey = 'id';
	protected $guarded    = array();

	public function getMusicListMessage($offset=0,array $where=array(),$limit=15){
		if(empty($where)){
			$list = $this->orderBy('totalComment','desc')->offset($offset)->limit($limit)->get();
			return $list;
		}
	}

	public function getTotalCount(array $where=array()){
		//用md5加密的方式
		$string 	= md5(json_encode($where));
		$totalCount = $this->Redis()->hget('cloudMusicMessage',$string);
		if(!$totalCount){
			$totalCount = $this->where($where)->count();
			$this->Redis()->hset('cloudMusicMessage',$string,$totalCount);
		}
		return $totalCount;
	}
}
