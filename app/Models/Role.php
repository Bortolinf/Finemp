<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantScoped;


class Role extends Model
{

    use TenantScoped;

   /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A role may have many abilities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function abilities()
    {
        return $this->belongsToMany(Ability::class)->withTimestamps();
    }

    public function abilitiesNames()
    {
        $names = [];
        $abs = $this->abilities;
        foreach ($abs as $ab) {
            $names[] = $ab->name;
        }
        return $names;
    }

    /**
     * Grant the given ability to the role.
     *
     * @param  mixed  $ability
     */
    public function allowTo($ability)
    {
        if (is_string($ability)) {
            $ability = Ability::whereName($ability)->firstOrFail();
        }
        //echo 'oi, cheguei no allowTo';
        //exit();
        $this->abilities()->sync($ability, false);
    }


    // retira uma permissao do grupo de usuarios
    public function disallowTo($ability)
    {
        if (is_string($ability)) {
            $ability = Ability::whereName($ability)->firstOrFail();
        }
        return $this->abilities()->detach($ability);
    }


}
