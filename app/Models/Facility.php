<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{

protected $table = 'facilities';

protected $fillable = [

'name',
'type',
'capacity',
'usage',
'status',
'last_maintenance',
'location',
'equipment',
'description'

];

}