<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagsController extends Controller
{
    public function store() {
        $tag = Tag::create($this->validateRequest());

        return redirect($tag->path());
    }

    public function update(Tag $tag) {
        $tag->update($this->validateRequest());

        return redirect($tag->path());
    }

    public function delete(Tag $tag) {
        $tag->delete();

        return redirect('/tags');
    }

    protected function validateRequest() {
        return request()->validate([
                    'name' => 'required',
                ]);
    }
}
