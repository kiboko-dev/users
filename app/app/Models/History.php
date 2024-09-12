<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use HasFactory;
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'histories';

    protected $fillable = [
        'model_id', 'model_name', 'before', 'after', 'action',
    ];
}
