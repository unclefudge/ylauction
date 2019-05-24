<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'address2', 'suburb', 'state', 'postcode', 'country',
        'password', 'password_reset', 'email_verified_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime',];

    /**
     * Get the first name (getter)
     *
     * @return string;
     */
    public function getFirstNameAttribute()
    {
        $arr = explode(' ', trim($this->name));

        return $arr[0];
    }

    /**
     * Get the first name (getter)
     *
     * @return string;
     */
    public function getBidderIdAttribute()
    {
        return $this->id + 10;
    }
}
