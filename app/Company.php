<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cnpj', 'address',
    ];

    public function users () {
         return $this->belongsToMany('\App\User', 'users_companies')->withTimestamps();
    }
}
