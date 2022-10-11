<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    use HasFactory;
     protected $fillable = ['name','scope_id'];
    public function resources(){
        return $this->hasMany(Resource::class);
    }
    public function scope(){
        return $this->belongsTo(Scope::class);
    }
}
