<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp() : void {
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
    public function test_can_store_user(){
       $response = $this->json('POST','api/users',
       ['first_name'=> 'gbessi',
        'last_name' => 'ken',
        'email'=>'gbessikenedy12@gmail.com',
        'setting_id' => 1, 
        'stats' =>['followers' => 2, 'following' => 4, 'rating' => 7],
        'current_subscription_status' =>"premium",
        'role_id' => 1,
        'country' => 'Benin',
        'password' =>Hash::make('password')]) ; 
        $response->assertStatus(201);
    }
    public function test_all_users(){
        $response = $this->json('GET','api/users');
        $this->assertCount(1,$response['data']);
    }
    public function test_can_update_user(){
        $response = $this->json('PUT','api/users/1',[
            'first_name'=> 'gbessi',
            'last_name' => 'NameUpdate',
            'professional_portofolio' => [
                "first_portfolio" => "http//history/first",
                "second_portfolio" => "http//history/second"
            ],
            'social' => ['0'=> "www.isSocial.com"]
        ]);
        $response->assertOk();
    }
    public function test_can_show_an_user(){
        $response = $this->json('GET','api/users/1');
        $this->assertEquals("kenedygbessi@gmail.com",$response['data']['email']);
    }
   
}
