<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;



    public function test_can_user_login_with_both_empty_fields()
    {
        $response = $this->json('POST', route('login'), [
            'email' => '',
            'password' => ''
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
    public function test_can_user_login_with_non_existent_identifier()
    {
        $response = $this->json('POST', route('login'), [
            'email' => 'gbessikenedy@test.com',
            'password' => 'otherpassword'
        ]);
        $response->assertStatus(404);
    }
    public function test_can_user_login()
    {
        User::factory()->create([
            'first_name' => 'nametotest',
            'last_name' => 'secondnametotest',
            'email' => 'kenedygbessi@gmail.com',
            'password' => 'itsapassword',
        ]);
        $response = $this->json('POST', route('login'), [
            'email' => 'kenedygbessi@gmail.com',
            'password' => 'itsapassword'
        ]);
        $response->assertStatus(200);
    }
    public function test_can_user_resets_password()
    {
        $this->withoutExceptionHandling();
        User::factory()->create([
            'first_name' => 'nametotest',
            'last_name' => 'secondnametotest',
            'email' => 'kengbessi@gmail.com',
            'password' => 'itsapassword',
        ]);
        $token = Password::createToken(User::first());
        // Event::fake();

        $response = $this->post(route('reset-password'), [
            'email' => 'kengbessi@gmail.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => $token
        ]);
        $this->assertTrue(Hash::check('newpassword', User::first()->password));
        // Event::assertDispatched(PasswordReset::class);
    }
    public function test_user_can_register()
    {
        $response = $this->json('POST', route('register'), [
            'email' => 'kenedy@gmail.com',
            'first_name' => 'LeBlanc',
            'last_name' => 'Ludovic Loisier',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(201);
    }
    public function test_user_can_register_with_bad_email()
    {
        $response = $this->json('POST', route('register'), [
            'email' => 'kenedygmail.com',
            'first_name' => 'LeBlanc',
            'last_name' => 'Ludovic Loisier',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertStatus(422);
    }
}
