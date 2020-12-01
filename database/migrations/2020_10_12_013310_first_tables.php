<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FirstTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('prefix', 30)->unique();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100);
            $table->rememberToken();
            $table->smallInteger('admin');
            $table->bigInteger('id_group')->unsigned()->nullable();
            $table->bigInteger('tenant_id')->unsigned();
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->timestamps();
            $table->bigInteger('tenant_id')->unsigned();
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->unique(['name', 'tenant_id']);
        });


        Schema::create('accounts', function (Blueprint $table) {
            $table->string('id_account', 15);
            $table->string('description', 100);
            $table->timestamps();
            $table->bigInteger('tenant_id')->unsigned();
            $table->string('type', 1); // A = analitico // S = sintetico
            $table->string('special_rule', 10)->nullable();     // alguma regra especial
            $table->string('special_rule_cod', 15)->nullable(); // algum código vinculado a regra especial
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->primary(['id_account', 'tenant_id']);

        });


        Schema::create('entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tenant_id')->unsigned();
            $table->date('date');
            $table->bigInteger('company_id')->unsigned();
            $table->string('account_id');
            $table->string('es', 1); // E = entrada(receita)  S = saída (despesa)
            $table->double('value')->unsigned();
            $table->text('info')->nullable();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('account_id')->references('id_account')->on('accounts');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
