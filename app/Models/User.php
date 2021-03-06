<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Auth;
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role','organization_id','current_team_id', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The list of user roles
     *
     * @return void
     */
    public static function userRoleList()
    {
        return [
            'admin' => 'Admin',
            'user' => 'User',
            'manager' => 'Manager'
        ];
    }

    public static function managerRolelist(){
        return [
            'manager' => 'Manager',
            'user' => 'User',
        ];
    }
     /**
     * The list of user roles
     *
     * @return void
     */
    public static function userOrganization()
    {
        return Organization::all();
    }

    public static function managerOrganization()
    {
        return Organization::where('id' , auth()->user()->organization_id)->get();
    }

    public function organizations()
    {
        return $this->belongsTo(Organization::class,'organization_id', 'id');
    }
}
