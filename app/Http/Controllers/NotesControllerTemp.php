<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NotesController extends Controller
{
    public function index() {
        $data = ["name" => "nige", "email" => "nige@mail.com"];

        return view("home", $data);
    }

    public function create() {
        
    }

    public function read() {
        
    }
    
    public function edit() {
        
    }
    
    public function remove() {
        
    }    

    public function test() {
        $note = Note::find(1);



        //var_dump($note->tags);
        //$note->tags()->sync([1,2]);
        //dd($note->tags);

        echo count($note->tags);

        foreach ($note->tags as $tag)
        {
            //var_dump($tag);
            echo $tag->name . "<br/>";
        }
    }

    public function store() {
        $note = Note::create($this->validateRequest());

        try {
            $note->tags()->sync(request()->input('tags'));
        }
        catch (\Exception $e) {
            $note->delete();
            return redirect($note->path())->withErrors(['tags' => 'Error attaching tags, please try again.'])->withInput(); 
        }

        return redirect($note->path());
    }

    public function update(Note $note) {
        $note->update($this->validateRequest());

        try {
            $note->tags()->sync(request()->input('tags'));
        }
        catch (\Exception $e) {
            return redirect($note->path())->withErrors(['tags' => 'Error attaching tags, please try again.'])->withInput(); 
        }

        return redirect($note->path());
    }

    public function delete(Note $note) {
        $note->delete();

        return redirect('/notes');
    }

    protected function validateRequest() {
        return request()->validate([
                    'name' => 'required',
                    'note' => ''
                ]);
    }
}
