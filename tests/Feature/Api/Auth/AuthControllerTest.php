<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
      
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
        $response->assertStatus(404);
    }
    public function test_show_validation_when_an_user_connected(){
        User::factory()->create([
            'first_name' => 'nametotest',
            'last_name' => 'secondnametotest',
            'email' => 'kenedygbessi@gmail.com',
            'password'=>'itsapassword',
        ]);
        $response = $this->json('POST',route('login'),[
            'email' => 'kenedygbessi@gmail.com',
            'password' => 'itsapassword'
        ]);
        $response->assertStatus(200);
    }
    public function test_show_if_method_to_reset_password_work(){
        User::factory()->create([
            'first_name' => 'nametotest',
            'last_name' => 'secondnametotest',
            'email' => 'kenedygbessi@gmail.com',
            'password'=>'itsapassword',
        ]);
        $token = Password::createToken(User::first());
        Event::fake();

        $response = $this->post(route('reset-password'), [
            'email' => 'kenedygbessi@gmail.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => $token
        ]);

        dump(User::first()->password);

        $response->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('newpassword', User::first()->password));
        
        Event::assertDispatched(PasswordReset::class);
    }
  
}
