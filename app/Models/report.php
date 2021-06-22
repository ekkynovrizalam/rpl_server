<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\DB;

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

    public function detailByClass($kelas,$startDate, $endDate)
    {
        return $this->join('student','student.user_id','=','reports.user_id')
            ->select('reports.*','student.nim','student.kelas','student.tim')
            ->where('kelas',$kelas)->where('created_at','>=',$startDate)->where('created_at','<=',$endDate)
            ->get();
    }

    public function resumeByClass($kelas,$startDate, $endDate)
    {
        return $this->join('students','students.user_id','=','reports.user_id')
            ->select(DB::raw('students.nim,students.kelas,students.tim, count(reports.user_id) as count'))
            ->where('kelas',$kelas)->where('reports.created_at','>=',$startDate)->where('reports.created_at','<=',$endDate)
            ->groupBy('reports.user_id','nim','kelas','tim')
            ->get();
    }


    
}
