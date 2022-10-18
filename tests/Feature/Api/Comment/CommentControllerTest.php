<?php

namespace Tests\Feature\Api\Comment;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp() : void {
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

        $comment = new Comment(['collection_id'=> 1,
        'content' => 'Belle resource !',
        'author_id' => 1
         ]);
        $comment->save();

    }
    public function test_can_store_comment(){
        $response = $this->json('POST','api/comments',
        ['collection_id'=> 1,
         'content' => 'Belle resource !',
         'author_id' => 1
        ]) ;
         $response->assertStatus(201);
     }
     public function test_all_comments(){
        $response = $this->json('GET','api/comments');
        $this->assertCount(1,$response['data']);
     }
     public function test_can_update_comment(){
        $response = $this->json('PUT','api/comments/1',[
            'content' => 'Belle resource updated !',
     ]);
     $response->assertOk();
    }
    public function test_can_show_an_comment(){
        $response = $this->json('GET','api/comments/1');
        $this->assertEquals("Belle resource !",$response['data']['content']);
    }
}
