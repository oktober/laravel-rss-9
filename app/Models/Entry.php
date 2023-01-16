<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_id',
        'entry_url', 
        'entry_title', 
        'entry_teaser', 
        'entry_content',
        'entry_last_updated',
    ];

    protected $casts = [
        'entry_last_updated' => 'datetime'
    ];

    public function feed() {
		return $this->belongsTo(Feed::class);
    }
}
