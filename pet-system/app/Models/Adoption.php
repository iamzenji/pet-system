<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adoption extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'name',
        'email',
        'contact',
        'address',
        'reason',
        'experience',
        'status',
        'adopted_date',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
}
