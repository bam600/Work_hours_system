<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exhibition;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'label'];

    public function exhibitions()
    {
    return $this->hasMany(Exhibition::class);
    }
}