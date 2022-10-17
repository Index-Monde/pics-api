<?php

namespace Tests\Feature\Api\Collection;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CollectionControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_store_collection(){
        $response = $this->json('POST','api/collections',
        ['name'=> 'first collection',
         'category_id' => 2,
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
        'category_id' => 2,
        'author_id' => 1
     ]);
     $response->assertOk();
    }
    public function test_can_show_an_collection(){
        $response = $this->json('GET','api/collections/1');
        $this->assertEquals("first collection updated",$response['data']['name']);
    }
}
