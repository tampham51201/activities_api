<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterActivity extends Model
{
    use HasFactory;
    protected $table ='register_activities';
    protected $fillable = [
        'id_activity',
        'id_user',
        'id_approver',
        'status',
    ];

    protected $with = ['activity','id_user','approver'];

    public Function activity()
    {
        return $this->belongsTo(Activities::class,'id_activity','id');
    }

   

    public Function id_user()
    {
        return $this->belongsTo(User::class,'id_user','id');
    }
    // protected $with2 = ['approver'];

    public Function approver()
    {
        return $this->belongsTo(User::class,'id_approver','id');
    }
}