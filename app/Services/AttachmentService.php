<?php

namespace App\Services;

use App\Models\Note;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentService {

    public function addFileAttachments($files, Note $note) {
        
        foreach($files as $file)
        {

            $name = time().rand(1,100).'.'.$file->extension();
            $realName = $file->getClientOriginalName();

            $path = Storage::putFileAs('files',$file,$name);

            $attachment = new Attachment();
            $attachment->location = $name;
            $attachment->name = $realName;
    
            $note->attachments()->save($attachment);

        }

        return $note;
    }

    public function removeFileAttachments($ids) {

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

}
