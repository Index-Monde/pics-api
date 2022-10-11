<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['resource_id','collection_id','content','author_id'];
    public function resource(){
        return $this->belongsTo(Resource::class);
    }
    public function collection(){
        return $this->belongsTo(Collection::class);
    }
    public function author(){
        return $this->belongsTo(User::class);
    }
}
