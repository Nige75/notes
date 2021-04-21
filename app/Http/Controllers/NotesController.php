<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NotesController extends Controller
{
    //
    public function store() {
        
        Note::create([
            'name' => request('name'),
            'note' => request('note')         
        ]);

    }
}
