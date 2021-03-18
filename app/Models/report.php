<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class report
 * @package App\Models
 * @version March 18, 2021, 6:50 am UTC
 *
 * @property string $yesterday
 * @property string $today
 * @property string $blocker
 * @property string $user_id
 */
class report extends Model
{

    public $table = 'reports';

    public $timestamps = true;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'yesterday',
        'today',
        'blocker',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'yesterday' => 'string',
        'today' => 'string',
        'blocker' => 'string',
        'user_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'yesterday' => 'required|string|max:255',
        'today' => 'required|string|max:255',
        'blocker' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'user_id' => 'nullable|string|max:255'
    ];

    
}
