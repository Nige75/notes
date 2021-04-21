<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Note;

class NoteActionsTest extends TestCase
{
use RefreshDatabase;

    /** @test */
    public function a_basic_note_can_be_inserted()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents'
        ]);

        $response->assertOk();
        $this->assertCount(1, Note::all());
    }
}
