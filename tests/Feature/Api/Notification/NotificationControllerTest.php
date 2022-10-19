<?php

namespace Tests\Feature\Api\Notification;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use App\Models\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationControllerTest extends TestCase
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

       $notification = new Notification(['title'=> 'A notification',
       'received_id' =>1,
       'content' => 'The first notification for user n°1'
        ]);
        $notification->save();
       
    }
    public function test_can_store_notification(){
        $response = $this->json('POST','api/notifications',
        ['title'=> 'first notification',
         'content' => "Une nouvelle notification" ,
         'received_id' => 1
        ]) ;
         $response->assertStatus(201);
     }
     public function test_all_notifications(){
        $response = $this->json('GET','api/notifications');
        $this->assertCount(1,$response['data']);
     }
     public function test_can_update_notification(){
        $response = $this->json('PUT','api/notifications/1',[
        'title'=> 'first notification updated',
     ]);
     $response->assertOk();
    }
    public function test_can_show_an_notification(){
        $response = $this->json('GET','api/notifications/1');
        $this->assertEquals("A notification",$response['data']['title']);
    }
}
