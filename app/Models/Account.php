<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantScoped;
use App\Traits\HasCompositePrimaryKey;

class Account extends Model
{
    use TenantScoped;
    use HasCompositePrimaryKey;

    protected $table = 'accounts';  // seta nome da tabela
    protected $primaryKey = 'id_account';  // seta o campo chave
    public $incrementing = false;    // seta campo chave como nao incremental

 
    public function entries()
    {
        return $this->belongsToMany(Entries::class)->withTimestamps();
    }


}

