<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tag extends Model
{
    use SoftDeletes;
//    public $dateFormat = 'U';
    protected $dates = ['delete_at'];
    //
}
