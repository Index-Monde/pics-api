<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\isEmpty;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_store_user(){
       $response = $this->json('POST','api/users',
       ['first_name'=> 'gbessi',
        'last_name' => 'ken',
        'email'=>'gbessikenedy12@gmail.com',
        'setting_id' => 2, 
        'stats' =>['followers' => 2, 'following' => 4, 'rating' => 7],
        'current_subscription_status' =>"premium",
        'role_id' => 1,
        'country' => 'Benin',
        'password' =>Hash::make('password')]) ; 
        $response->assertStatus(201);
    }
    public function test_all_users(){
        $response = $this->json('GET','api/users');
        $this->assertCount(3,$response['data']);
    }
    public function test_can_update_user(){
        $response = $this->json('PUT','api/users/2',[
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
