<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloudPlayList extends Model
{
	protected $table   = 'cloud_play_list';
	protected $primaryKey = 'listId';
	protected $guarded = array();
}
