<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tag;
use App\Models\NoteTag;

class TagsActionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_tag_can_be_inserted()
    {
        $this->withoutExceptionHandling();

        $this->post('/tags', [
            'name' => 'tag1'
        ]);

        $this->assertCount(1, Tag::all());
    }

     /** @test */
     public function a_name_is_required()
     {
 
         $response = $this->post('/tags',[
             'name' => ''
         ]);
 
         $response->assertSessionHasErrors('name');
     }
 
     /** @test */
     public function a_basic_tag_can_be_updated()
     {
         $this->withoutExceptionHandling();
 
         $this->post('/tags',[
             'name' => 'tag1'
         ]);
 
         $tag = Tag::first();
 
         $response = $this->patch($tag->path(),[
             'name' => 'new tag'
         ]);
 
         $this->assertEquals('new tag', Tag::first()->name);   
 
         $response->assertRedirect('/tags');
     
     }
 
     /** @test */
     public function a_tag_can_be_deleted() 
     {
         $this->post('/tags',[
             'name' => 'tag1'
         ]);
 
         $tag = Tag::first();
         $this->assertCount(1, Tag::all()); 
 
         $id = $tag->id;

         $response = $this->delete($tag->path());
 
         $this->assertCount(0, NoteTag::where('tag_id',$id)->get());
         $this->assertCount(0, Tag::all());   
         $response->assertRedirect('/tags');
     }
}
