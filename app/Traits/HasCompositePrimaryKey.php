<?php

namespace App\Traits;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasCompositePrimaryKey {


    /**
     * Set the keys for a save update query.
     *
     * @param  mixed  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
            parent::setKeysForSaveQuery($query);
            $query->where('tenant_id', '=', Auth::user()->tenant_id);
            return $query;
    }

}