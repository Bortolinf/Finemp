<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\TenantScoped;

class Entry extends Model
{
    use HasFactory;
    use TenantScoped;
    protected $table = 'entries';  // seta nome da tabela

    public function account()
    {
        return $this->hasOne('App\Models\Account', 'id_account', 'account_id');
    }

    public function company()
    {
        return $this->hasOne('App\Models\Company');
    }


    public function scopeSearch($query, $q)
    {
        if ($q == null) return $query;

        return $query
            ->where('date', 'LIKE', "%{$q}%")
            ->orWhere('value', 'LIKE', "%{$q}%")
            ->orWhere('es', 'LIKE', "%{$q}%")
            ->orWhere('info', 'LIKE', "%{$q}%")
            ->orWhereHas('account', function (Builder $query) use ($q) {
                $query->where('description', 'like', "%{$q}%");
            });
    }
}
