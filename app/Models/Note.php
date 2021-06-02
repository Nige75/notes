<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['name','note','file'];

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function attachments() {
        return $this->hasMany(Attachment::class);
    }


    public function path() {
        return '/notes/' . $this->id;
    }
}
