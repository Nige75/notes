<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Note;
use App\Models\User;
use Laravel\Passport\Passport;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class NotesApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_logged_in_user_can_access_api() 
    {

        \Artisan::call('passport:install');

        // insert some test data
        $response = $this->post('/notes',[
            'name' => 'note name',
            'note' => 'the note contents'
        ]);

        // create user
        User::factory()->create([
            'name' => 'testuser',
            'email' => 'test@test.com',
            'password' => Hash::make('pass')
        ]
        );

        $this->assertEquals('testuser', User::first()->name);

        // get bearer token
        $oauth_client_id = 2; 
        $oauth_client = DB::table('oauth_clients')->where('id', $oauth_client_id)->first();
    
        $body = [
            'username' => 'test@test.com',
            'password' => 'pass',
            'client_id' => $oauth_client_id,
            'client_secret' => $oauth_client->secret,
            'grant_type' => 'password',
            'scope' => '*'
        ];


        $response = $this->json('POST','/oauth/token',$body,['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token_type','expires_in','access_token','refresh_token']);

        
        // test api call
        $body = [];

        $response = $this->json('GET','/api/v1/notes',$body,['Accept' => 'application/json','Authorization' => 'Bearer '. $response['access_token']]);

        $response->assertJsonStructure(['data' => [['id','type','attributes' => ['name','note','created_at','updated_at','tags','attachments']]]]);

        $response->assertStatus(200);

    }

    /** @test */
    public function an_unlogged_in_user_cannot_access_api() 
    {
        \Artisan::call('passport:install');

        // create user
        User::factory()->create([
            'name' => 'testuser',
            'email' => 'test@test.com',
            'password' => Hash::make('pass')
        ]
        );

        $this->assertEquals('testuser', User::first()->name);

        // test api call without bearer token
        $body = [];

        $response = $this->json('GET','/api/v1/notes',$body,['Accept' => 'application/json','Authorization' => 'Bearer xxx']);

        // check response is 401 (unauthorized)
        $response->assertStatus(401);
    }
}
