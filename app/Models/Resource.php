<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type_id',
        'need_plan_activation',
        'author_id',
        'category_id',
        'stats',
        'size',
        'resource_url',
        'extension',
        'status',
    ];
    public function type(){
        return $this->belongsTo(Type::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function collections(){
        return $this->belongsToMany(Collection::class,'resource_collection');
    }
    public function author(){
        return $this->belongsTo(User::class);
    }
    public function stats(): Attribute{
        return Attribute::make(
           get : fn ($value) => json_decode($value,true),
           set : fn ($value) => json_encode($value)
        );
   }
}
