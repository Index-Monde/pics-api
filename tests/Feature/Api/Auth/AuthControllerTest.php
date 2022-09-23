<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp():void{
        parent::setUp();
        Notification::fake();
        User::create([
                'firstname'=>'Kenedy',
                'lastname'=>'GBESSI',
                'email'=>'gbessikenedy@gmail.com',
                'photo_url' => 'http//photo',
                'type_abonnement'=>'subscriber',
                'number_of_followers'=>15,
                'number_of_following'=>150,
                'password'=>Hash::make('testPassword'),
                'confirm_password'=>Hash::make('testPassword')
            ]
        );
    }
        
    public function test_show_error_when_user_connect_with_both_empty_fields(){
        $response = $this->json('POST',route('login'),[
            'email' => '',
            'password' => ''
        ]);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email','password']);
    }
    public function test_show_validation_error_when_an_user_connected(){
        $response = $this->json('POST',route('login'),[
            'email' => 'gbessikenedy@test.com',
            'password' => 'otherpassword'
        ]);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
  
}
