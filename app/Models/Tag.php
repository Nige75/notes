<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;


    public function notes() {
        return $this->belongsToMany(Note::class);
    }

    public function path() {
        return '/tags/' . $this->id;
    }
}
