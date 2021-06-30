<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Linkeditem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'item_id', 'linked_item_id','created_by','updated_by'
    ];
    
}
