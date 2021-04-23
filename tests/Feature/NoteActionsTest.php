<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Note;
use App\Models\Tag;
use App\Models\NoteTag;

class NoteActionsTest extends TestCase
{
use RefreshDatabase;

    /** @test */
    public function a_basic_note_can_be_inserted()
    {

        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents'
        ]);

        $note = Note::first();

        $this->assertCount(1, Note::all());

        $response->assertRedirect($note->path());
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

        $response->assertRedirect($note->fresh()->path());
    
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

        $response->assertRedirect($note->path());
    }

    /** @test */
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

    /** @test */
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

        $response->assertRedirect($note->fresh()->path());
    
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


}