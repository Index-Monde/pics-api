<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function types(){
        return $this->hasMany(Type::class);
    }
}
