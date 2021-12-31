<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Activities extends Model
{
    use HasFactory;
    protected $table ='activities';
    protected $fillable = [
        'title',
        'description',
        'quantity',
        'quantity_register',

        'point',
        'category_id',
        'img',
        
        'time_start_activity',
        'time_end_activity',
     
        'time_start_register',
        'time_end_register',
        'status',
       
    ];

    protected $with = ['category'];

    public Function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}