<?php

namespace Tests\Feature\Api\Auth;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp():void{
        parent::setUp();
        $setting = new Setting(['key' => 'l1','value' => 'français']);
        $setting->save();
        $role = new Role(['name' => 'user','description' => 'A simple user']);
        $role->save();
        User::factory()->create([
            'first_name' => 'nametotest',
            'last_name' => 'secondnametotest',
            'email' => 'kenedygbessi@gmail.com',
            'password' => 'itsapassword',
            'setting_id'=>1,
            'role_id' => 1,
            'current_subscription_status' => 'premium'
        ]);

    }
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
    public function test_user_can_register()
    {
        $response = $this->json('POST', route('register'), [
            'email' => 'kenedy@gmail.com',
            'first_name' => 'LeBlanc',
            'last_name' => 'Ludovic Loisier',
            'password' => 'password',
            'password_confirmation' => 'password',
            'setting_id' => 1,
            'role_id' => 1
        ]);
        $response->assertStatus(201);
    }
    public function test_can_user_login()
    {
        $response = $this->json('POST', route('login'), [
            'email' => 'kenedygbessi@gmail.com',
            'password' => 'itsapassword'
        ]);
        $response->assertStatus(200);
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
