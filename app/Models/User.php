<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\TenantScoped;


class User extends Authenticatable
{
    use Notifiable;
  //  use TenantScoped;

    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'tenant_id', 'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * A user may be assigned many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }


    public function rolesNames()
    {
        $names = [];
        $roles = $this->roles;
        foreach ($roles as $role) {
            $names[] = $role->name;
        }
        return $names;
    }



    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::whereName($role)->firstOrFail();
        }
       // $this->roles()->syncWithoutDetaching($role);
        $this->roles()->sync($role, false);

    }


    // retira uma role do usuario
    public function disassignRole($role)
    {
        if (is_string($role)) {
            $role = Role::whereName($role)->firstOrFail();
        }
        return $this->roles()->detach($role);
    }




    /**
     * Fetch the user's abilities.
     *
     * @return array
     */
    public function abilities()
    {
        return $this->roles
            ->map->abilities
            ->flatten()->pluck('name')->unique();
    }


}
