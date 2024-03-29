<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\TenantScoped;


class Setting extends Model
{
    use HasFactory;
    use TenantScoped;
    protected $table = 'settings';  // seta nome da tabela

    public function income_account()
    {
        return $this->hasOne('App\Models\Account', 'id_account', 'income_account');
    }

    public function expense_account()
    {
        return $this->hasOne('App\Models\Account', 'id_account', 'expense_account');
    }

}
