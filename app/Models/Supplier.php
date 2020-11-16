<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantScoped;


class Supplier extends Model
{
    use TenantScoped;

    protected $table = 'suppliers';  // seta nome da tabela
    protected $primaryKey = 'uuid';  // seta o campo chave
    public $incrementing = false;    // seta campo chave como nao incremental

    public function municipio() {
        return $this->hasOne(Municipio::class, 'id', 'municipio_id');
    }
}
