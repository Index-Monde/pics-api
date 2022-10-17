<?php

namespace Tests\Feature\Api\Comment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_store_collection(){
        $response = $this->json('POST','api/comments',
        ['resource_id'=> 1,
         'content' => 'Belle resource !',
         'author_id' => 2
        ]) ;
         $response->assertStatus(201);
     }
     public function test_all_collections(){
        $response = $this->json('GET','api/comments');
        $this->assertCount(1,$response['data']);
     }
     public function test_can_update_collection(){
        $response = $this->json('PUT','api/comments/1',[
            'content' => 'Belle resource updated !',
     ]);
     $response->assertOk();
    }
    public function test_can_show_an_collection(){
        $response = $this->json('GET','api/comments/1');
        $this->assertEquals("Belle resource updated !",$response['data']['content']);
    }
}
