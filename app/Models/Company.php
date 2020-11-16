<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantScoped;

class Company extends Model
{
    use TenantScoped;

    protected $table = 'companies';  // seta nome da tabela
}
