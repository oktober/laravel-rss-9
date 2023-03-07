<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;
    
	protected $fillable = [
		'user_id', 
		'feed_url', 
		'site_url', 
		'site_title'
	];

    public function user() {
		return $this->belongsTo(User::class);
    }

    public function entries(){
    	return $this->hasMany(Entry::class);
    }
}
