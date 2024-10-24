<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedCapsule extends Model
{
    use HasFactory;

    protected $table = 'receivedcapsules'; // Specify the correct table name
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'receiver_email',
        'scheduled_open_at'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function images() { // Change to images to reflect the relationship correctly
        return $this->morphMany(Image::class, 'capsule');
    }
}
