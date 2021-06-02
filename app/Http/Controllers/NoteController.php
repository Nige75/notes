<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Tag;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = Note::all();
        return view('note.index',compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        return view('note.create',compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = Note::create($this->validateRequest());
        $note->tags()->sync(request()->input('tags'));

        $note = $this->addFileAttachments($request,$note);

        //ddd($note);

        //$note->attachments()->sync($ids);

        return redirect('/notes')->with('completed', 'Note has been created');
    }

    protected function addFileAttachments(Request $request, Note $note) {
        
        if($request->hasfile('filenames'))
        {

            foreach($request->file('filenames') as $file)
            {

                $name = time().rand(1,100).'.'.$file->extension();
                $realName = $file->getClientOriginalName();

                $path = Storage::putFileAs('files',$file,$name);

                $attachment = new Attachment();
                $attachment->location = $name;
                $attachment->name = $realName;
        
                $note->attachments()->save($attachment);

            }

        }

         return $note;
    }

    protected function removeFileAttachments($ids) {

        if(!empty($ids)){
            foreach ($ids as $id) {

                // remove files
                $attachment = Attachment::findOrFail($id);
                $fileName = storage_path('app/files/' . $attachment->location);

                unlink($fileName);
                //Storage::delete($fileName);

                // remove from db
                $attachment->delete();

            }
        }
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Note::findOrFail($id);
        return view('note.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $note = Note::findOrFail($id);

        $tagids = $note->tags->pluck('id')->toArray();

        $tags = Tag::all();
        return view('note.edit', compact('note','tagids','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);
        $note->update($this->validateRequest());

        $note->tags()->sync($request->input('tags'));

        //$note->attachments()->detach($request->input('removeAttachments'));
        $this->removeFileAttachments($request->input('removeAttachments'));


        $note = $this->addFileAttachments($request, $note);
        //$note->attachments()->attach($ids);

        return redirect('/notes')->with('completed', 'Note has been updated');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Note::findOrFail($id);

        $this->removeFileAttachments($note->attachments()->get()->pluck('id'));

        $note->delete();

        return redirect('/notes')->with('completed', 'Note has been deleted');
    }

     /**
     * Download attachment in browser
     *
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $attachment = Attachment::findOrFail($id);

        $fileName = storage_path('app/files/' . $attachment->location);

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$attachment->name."\""); 
        readfile($fileName);
        exit;

    }

    protected function validateRequest() {
        return request()->validate([
                    'name' => 'required',
                    'note' => '',
                ]);
    }
}
