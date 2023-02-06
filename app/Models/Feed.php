<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;
    
	protected $fillable = [
		'feed_url', 
		'site_url', 
		'site_title'
	];

    public function entries(){
    	return $this->hasMany(Entry::class);
    }
}
