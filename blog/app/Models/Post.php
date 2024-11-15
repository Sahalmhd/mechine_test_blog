<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Specify the fields that are mass assignable
    protected $fillable = [
        'name', 'date', 'author', 'content', 'image'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
