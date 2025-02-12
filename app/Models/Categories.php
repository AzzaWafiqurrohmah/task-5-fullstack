<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'name'
    ];

    public function articles() :HasMany
    {
        return this->hasMany(Article::class);
    }

    public function user()
    {
        return this->belongsTo(User::class);
    }
}
