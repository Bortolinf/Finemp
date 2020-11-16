<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantScoped;

class Entry extends Model
{
    use HasFactory;
    use TenantScoped;
    protected $table = 'entries';  // seta nome da tabela

    public function account()
    {
        return $this->hasOne('App\Models\Account', 'accont_id', 'id_account');
    }

    public function company()
    {
        return $this->hasOne('App\Models\Company');
    }


}
