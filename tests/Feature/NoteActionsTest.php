<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Note;
use App\Models\Tag;
use App\Models\NoteTag;
use App\Models\AttachmentNote;
use App\Models\Attachment;

class NoteActionsTest extends TestCase
{
use RefreshDatabase;

    /** @test */
    public function index_route_can_be_reached_and_display_notes() {
        
        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents'
        ]);

        $response = $this->get('/notes');

        $response->assertStatus(200);
        $response->assertViewHas('notes', Note::all());
        $response->assertSee('note name');
    }

    /** @test */    
    public function a_basic_note_can_be_inserted()
    {

        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents'
        ]);

        //$note = Note::first();

        $this->assertCount(1, Note::all());

        $response->assertStatus(302);
        $response->assertRedirect('/notes');  
    }

    /** @test */   
    public function a_name_is_required()
    {

        $response = $this->post('/notes',[
            'name' => '',
            'note' => 'the note contents'
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */   
    public function a_basic_note_can_be_updated()
    {

        $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents'
        ]);

        $note = Note::first();

        $response = $this->patch($note->path(),[
            'name' => 'a new note name',
            'note' => 'new note contents'
        ]);

        $this->assertEquals('a new note name', Note::first()->name);
        $this->assertEquals('new note contents', Note::first()->note);    

        $response->assertRedirect('/notes');
    
    }

    /** @test */   
    public function a_basic_note_can_be_deleted() 
    {
        $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents'
        ]);

        $note = Note::first();
        $this->assertCount(1, Note::all()); 

        $response = $this->delete($note->path());

        $this->assertCount(0, Note::all());   
        $response->assertRedirect('/notes');
    }

    /** @test */ 
    public function a_note_with_tags_can_be_inserted()
    {

        $this->post('/tags', [
            'name' => 'tag1'
        ]);

        $this->post('/tags', [
            'name' => 'tag2'
        ]);

        $this->post('/tags', [
            'name' => 'tag3'
        ]);

        $this->assertCount(3, Tag::all());

        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents',
            'tags' => [1,2]
        ]);

        $note = Note::first();

        $this->assertCount(1, Note::all());
        $this->assertCount(2, $note->tags);

        $this->assertEquals('tag1', Note::first()->tags[0]->name);
        $this->assertEquals('tag2', Note::first()->tags[1]->name);

        $response->assertRedirect('/notes');
    }

    public function a_note_insert_with_invalid_tags_returns_a_tag_error_and_removes_note()
    {
        
        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents',
            'tags' => [1,2]
        ]);

        $response->assertSessionHasErrors();
        $errors = session('errors');
        $this->assertEquals($errors->get("tags")[0],"Error attaching tags, please try again.");
    
        $this->assertCount(0, Note::all());
    
    }

    public function a_note_update_with_invalid_tags_returns_a_tag_error()
    {

        $this->post('/tags', [
            'name' => 'tag1'
        ]);

        $this->post('/tags', [
            'name' => 'tag2'
        ]);
        
        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents',
            'tags' => [1,2]
        ]);

        $note = Note::first();

        $response = $this->patch($note->path(),[
            'name' => 'a new note name',
            'note' => 'new note contents',
            'tags' => [1,3]
        ]);

        $response->assertSessionHasErrors();
        $errors = session('errors');
        $this->assertEquals($errors->get("tags")[0],"Error attaching tags, please try again.");

    
    }

    /** @test */ 
    public function a_note_with_tags_can_be_updated()
    {

        $this->post('/tags', [
            'name' => 'tag1'
        ]);

        $this->post('/tags', [
            'name' => 'tag2'
        ]);

        $this->post('/tags', [
            'name' => 'tag3'
        ]);

        $this->assertCount(3, Tag::all());

        $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents',
            'tags' => [1,2]
        ]);

        $note = Note::first();

        $this->assertCount(1, Note::all());
        $this->assertCount(2, $note->tags);

        $this->assertEquals('tag1', Note::first()->tags[0]->name);
        $this->assertEquals('tag2', Note::first()->tags[1]->name);


        $note = Note::first();

        $response = $this->patch($note->path(),[
            'name' => 'a new note name',
            'note' => 'new note contents',
            'tags' => [1,3]
        ]);

        $this->assertEquals('a new note name', Note::first()->name);
        $this->assertEquals('new note contents', Note::first()->note);    

        $this->assertEquals('tag1', Note::first()->tags[0]->name);
        $this->assertEquals('tag3', Note::first()->tags[1]->name);

        $response->assertRedirect('/notes');
    
    }

        /** @test */ 
        public function a_note_with_tags_can_be_deleted() 
        {
            $this->post('/tags', [
                'name' => 'tag1'
            ]);
    
            $this->post('/tags', [
                'name' => 'tag2'
            ]);
    
            $this->assertCount(2, Tag::all());
    
            $this->post('/notes',[
                'name' => 'note name',
                'note' => 'the note contents',
                'tags' => [1,2]
            ]);
    
            $note = Note::first();
    
            $this->assertCount(1, Note::all());
            $this->assertCount(2, $note->tags);
    
            $this->assertEquals('tag1', Note::first()->tags[0]->name);
            $this->assertEquals('tag2', Note::first()->tags[1]->name);
    
            $note = Note::first();
            $id = $note->id;
            $this->assertCount(1, Note::all()); 
    
            $response = $this->delete($note->path());
    
            $this->assertCount(0, Note::all()); 
            $this->assertCount(0, NoteTag::where('note_id',$id)->get());
            $response->assertRedirect('/notes');
        }

        /** @test */ 
    public function a_note_with_file_attachment_can_be_inserted()
    {

        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents',
            'filenames' => [UploadedFile::fake()->image('avatar.jpg')]
        ]);

        $note = Note::first();

        $this->assertCount(1, Note::all());
        $this->assertCount(1, $note->attachments()->get());

        $this->assertFileExists(storage_path('app/files/' . Note::first()->attachments()->first()->location));

        $response->assertRedirect('/notes');

        // cleanup, remove temp file

        unlink(storage_path('app/files/' . Note::first()->attachments()->first()->location));
    }

    /** @test */ 
    public function a_note_with_several_file_attachments_can_be_inserted()
    {

        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents',
            'filenames' => [UploadedFile::fake()->image('avatar.jpg'), UploadedFile::fake()->image('avatar2.jpg')]
        ]);

        $note = Note::first();

        $attachments = Note::first()->attachments()->get()->all();

        $this->assertCount(2, $attachments);
            //ddd($attachments);

        $this->assertCount(1, Note::all());

        $this->assertFileExists(storage_path('app/files/' . $attachments[0]->location));
        $this->assertFileExists(storage_path('app/files/' . $attachments[1]->location));    

        $this->assertEquals('avatar.jpg', $attachments[0]->name);
        $this->assertEquals('avatar2.jpg', $attachments[1]->name);

        $response->assertRedirect('/notes');

        // cleanup, remove temp file

        unlink(storage_path('app/files/' . $attachments[0]->location));
        unlink(storage_path('app/files/' . $attachments[1]->location));
    }

    /** @test */ 
    public function a_note_with_several_file_attachments_can_be_updated()
    {

        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents',
            'filenames' => [UploadedFile::fake()->image('avatar.jpg'), UploadedFile::fake()->image('avatar2.jpg')]
        ]);

        $note = Note::first();
        $attachments = Note::first()->attachments()->get()->all();

        $this->assertCount(2, $attachments);
        $this->assertCount(1, Note::all());

        $this->assertFileExists(storage_path('app/files/' . $attachments[0]->location));
        $this->assertFileExists(storage_path('app/files/' . $attachments[1]->location));    

        $this->assertEquals('avatar.jpg', Note::first()->attachments[0]->name);
        $this->assertEquals('avatar2.jpg', Note::first()->attachments[1]->name);

        $response->assertRedirect('/notes');

        $response = $this->patch('/notes/' . $note->id,[
            'name' => 'note name',
            'note' => 'the note contents',
            'filenames' => [UploadedFile::fake()->image('avatar3.jpg'), UploadedFile::fake()->image('avatar4.jpg')],
            'removeAttachments' => [$note->attachments[0]->id, $note->attachments[1]->id]
        ]);

        $this->assertCount(1, Note::all());

        $this->assertFileDoesNotExist(storage_path('app/files/' . $note->attachments[0]->location));
        $this->assertFileDoesNotExist(storage_path('app/files/' . $note->attachments[1]->location));  

        $this->assertFileExists(storage_path('app/files/' . Note::first()->attachments[0]->location));
        $this->assertFileExists(storage_path('app/files/' . Note::first()->attachments[1]->location));    

        $this->assertEquals('avatar3.jpg', Note::first()->attachments[0]->name);
        $this->assertEquals('avatar4.jpg', Note::first()->attachments[1]->name);

        $response->assertRedirect('/notes');

        // cleanup, remove temp file

        unlink(storage_path('app/files/' . Note::first()->attachments[0]->location));
        unlink(storage_path('app/files/' . Note::first()->attachments[1]->location));
    }

        /** @test */ 
        public function a_note_with_several_file_attachments_and_tags_can_be_deleted()
        {
    
            $this->post('/tags', [
                'name' => 'tag1'
            ]);
    
            $this->post('/tags', [
                'name' => 'tag2'
            ]);

            $response = $this->post('/notes',[
                'name' => 'note name',
                'note' => 'the note contents',
                'filenames' => [UploadedFile::fake()->image('avatar.jpg'), UploadedFile::fake()->image('avatar2.jpg')],
                'tags' => [1,2]
            ]);
    
            $note = Note::first();
    
            $this->assertCount(1, Note::all());
    
            $this->assertFileExists(storage_path('app/files/' . $note->attachments[0]->location));
            $this->assertFileExists(storage_path('app/files/' . $note->attachments[1]->location));    
    
            $this->assertEquals('avatar.jpg', Note::first()->attachments[0]->name);
            $this->assertEquals('avatar2.jpg', Note::first()->attachments[1]->name);
    
            $this->assertCount(1, Note::all());
            $this->assertCount(2, NoteTag::all());   
            $this->assertCount(2, Attachment::all());

            $response->assertRedirect('/notes');
    
            $response = $this->delete('/notes/' . $note->id);
    
            $this->assertCount(0, Note::all());
            $this->assertCount(0, NoteTag::all()); 
            $this->assertCount(0, Attachment::all());

            $this->assertFileDoesNotExist(storage_path('app/files/' . $note->attachments[0]->location));
            $this->assertFileDoesNotExist(storage_path('app/files/' . $note->attachments[1]->location));  
    
            $response->assertRedirect('/notes');
    
        }
}