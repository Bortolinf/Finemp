<?php

namespace App\Traits;

use App\Models\TenantScope;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait TenantScoped

{
   protected static function bootTenantScoped()
   {
        static::creating(function (Model $model) {
            $model->tenant_id = Auth::user()->tenant_id;
	});
        
        static::addGlobalScope(new TenantScope);
   }
   public function tenant()
   {
       return $this->belongsTo(Tenant::class);
   }
}