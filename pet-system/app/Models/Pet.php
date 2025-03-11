<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pets';

    protected $fillable = [
        'type',
        'breed',
        'gender',
        'color',
        'size',
        'age',
        'weight',
        'image',
        'temperament',
        'health_status',
        'spayed_neutered',
        'vaccination_status',
        'good_with',
        'adoption_status'
    ];
}
