<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Tag;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use App\Services\AttachmentService;

class NoteController extends Controller
{

    private $attachmentService;

    public function __construct(AttachmentService $service)
    {
        $this->attachmentService = $service;
    }

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

        if($request->hasfile('filenames'))
        {
            $note = $this->attachmentService->addFileAttachments($request->file('filenames'),$note);
        }

        return redirect('/notes')->with('completed', 'Note has been created');
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

        $this->attachmentService->removeFileAttachments($request->input('removeAttachments'));

        if($request->hasfile('filenames'))
        {
            $note = $this->attachmentService->addFileAttachments($request->file('filenames'),$note);
        }

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

        $this->attachmentService->removeFileAttachments($note->attachments()->get()->pluck('id'));

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
