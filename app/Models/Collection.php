<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
{
    use HasFactory;
    protected $fillable = ['name','category_id','resource_ids','author_id','stats'];
    public function author(){
        return $this->belongsTo(User::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function resource_ids(): Attribute{
         return Attribute::make(
            get : fn ($value) => json_decode($value,true),
            set : fn ($value) => json_encode($value)
         );
    }
    public function stats(): Attribute{
        return Attribute::make(
           get : fn ($value) => json_decode($value,true),
           set : fn ($value) => json_encode($value)
        );
   }
}
