<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class student
 * @package App\Models
 * @version March 18, 2021, 6:43 am UTC
 *
 * @property string $nama
 * @property string $nim
 * @property string $kelas
 * @property string $tim
 * @property string $user_id
 */
class student extends Model
{

    public $table = 'students';
    public $timestamps = true;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'nama',
        'nim',
        'kelas',
        'tim',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nama' => 'string',
        'nim' => 'string',
        'kelas' => 'string',
        'tim' => 'string',
        'user_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nama' => 'required|string|max:255',
        'nim' => 'required|string|max:255',
        'kelas' => 'required|string|max:255',
        'tim' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'user_id' => 'nullable|string|max:255'
    ];

    
}
