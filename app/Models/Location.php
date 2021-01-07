<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = "locations";
    protected $guarded = [];
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
