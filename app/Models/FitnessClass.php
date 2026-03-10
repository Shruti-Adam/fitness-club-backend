<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FitnessClass extends Model
{
    protected $table = 'fitness_classes';

    protected $fillable = [
        'name',
        'description',
        'trainer',
        'category',
        'intensity',
        'duration',
        'time',
        'price',
        'slots',
        'image',
        'status'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset('class_images/'.$this->image);
    }

    

}