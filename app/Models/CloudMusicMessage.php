<?php

namespace App\Models;

class CloudMusicMessage extends Common
{
	protected $table      = 'cloud_music_message';
	protected $primaryKey = 'id';
	protected $guarded    = array();

	public function getMusicListMessage(array $where=array(),$offset=0,$limit=15){
		if(empty($where)){
			$newModel = $this->orderBy('totalComment','desc')->offset($offset)->limit($limit)->get();
			$list 	  = $this->queryByPage($newModel,$offset,$limit);
			return $list;
		}
	}
}
