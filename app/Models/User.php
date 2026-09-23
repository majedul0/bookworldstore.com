<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Active
    public function scopeActive($q, $order = 'id', $type = null, $take = null){
        $q->where('status', 1);
        if($type){
            $q->where('type', $type);
        }
        $q->latest($order);
        if($take){
            $q->take($take);
        }
    }

    public function getStatusStringAttribute(){
        if($this->status == 0){
            return 'Suspended';
        }elseif($this->status == 2){
            return 'Pending';
        }
        return 'Active';
    }

    // Profile paths
    public function getProfilePathAttribute(){
        if($this->profile && file_exists(public_path('uploads/user/' . $this->profile))){
            return asset('uploads/user/' . $this->profile);
        }

        return asset('img/user-img.jpg');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
