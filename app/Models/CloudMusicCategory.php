<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloudMusicCategory extends Model
{
	protected $table      = 'cloud_music_category';
	protected $primaryKey = 'cateId';
	protected $guarded    = array();
}
