<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Capsule extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'message',
        'content',
        'receiver_email',
        'scheduled_open_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function images() { // Change to images to reflect the relationship correctly
        return $this->morphMany(Image::class, 'capsule'); // Use the correct model name
    }
    
}
