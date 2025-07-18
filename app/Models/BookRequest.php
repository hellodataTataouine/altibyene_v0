<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;
    protected $fillable = ['name','school','address', 'email', 'phone', 'message','is_readed'];
}
