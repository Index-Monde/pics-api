<?php

namespace Tests\Feature\Api\Collection;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollectionControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp():void{
       parent::setUp();
       $setting = new Setting(['key' => 'l1','value' => 'français']);
       $setting->save();

       $role = new Role(['name' => 'user','description' => 'A simple user']);
       $role->save();

       $category= new Category(['name' => 'Media','description' => 'resources of medias',]);
       $category->save();

       User::factory()->create([
        'first_name' => 'nametotest',
        'last_name' => 'secondnametotest',
        'email' => 'kenedygbessi@gmail.com',
        'password' => 'itsapassword',
        'setting_id'=>1,
        'role_id' => 1,
        'current_subscription_status' => 'premium'
       ]);
       $collection = new Collection(['name'=> 'A collection',
       'category_id' =>1 ,
       'stats' =>['followers' => 2, 'following' => 4, 'rating' => 7],
       'author_id' => 1
        ]);
        $collection->save();
    }
    public function test_can_store_collection(){
        $response = $this->json('POST','api/collections',
        ['name'=> 'first collection',
         'category_id' =>1 ,
         'stats' =>['followers' => 2, 'following' => 4, 'rating' => 7],
         'author_id' => 1
        ]) ;
         $response->assertStatus(201);
     }
     public function test_all_collections(){
        $response = $this->json('GET','api/collections');
        $this->assertCount(1,$response['data']);
     }
     public function test_can_update_collection(){
        $response = $this->json('PUT','api/collections/1',[
        'name'=> 'first collection updated',
     ]);
     $response->assertOk();
    }
    public function test_can_show_an_collection(){
        $response = $this->json('GET','api/collections/1');
        $this->assertEquals("A collection",$response['data']['name']);
    }
}
