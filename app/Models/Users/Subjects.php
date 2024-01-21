<?php
use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;
use App\Models\Users\Subjects;

class Subjects extends Model
{
    const UPDATED_AT = null;


    protected $fillable = [
        'subject'
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'subject_users', 'subject_id', 'user_id');// リレーションの定義
    }
}