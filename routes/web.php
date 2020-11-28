<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// rota geral do site
Route::get('/', 'Site\HomeController@index');


// rota para Registrar um novo Tenant
Route::prefix('tenant')->group(function(){
    Route::get('register', 'Admin\TenantController@index')->name('registerTenant');
    Route::post('register', 'Admin\TenantController@register')->name('tenant.save');
}); 



//prefixo painel
Route::prefix('painel')->group(function(){
    Route::get('/', 'Admin\HomeController@index')->name('admin');
    
    Route::get('login', 'Admin\Auth\LoginController@index')->name('login');
    Route::post('login', 'Admin\Auth\LoginController@authenticate');
    Route::post('logout', 'Admin\Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Admin\Auth\RegisterCocntroller@index')->name('register');
    Route::post('register', 'Admin\Auth\RegisterController@register');

    // esse comando gera todas as rotas do crud das paginas
    Route::resource('pages', 'Admin\PageController');
    
    Route::get('/profile', 'Admin\ProfileController@index')->name('profile');
    Route::put('/profilesave', 'Admin\ProfileController@save')->name('profile.save');
    
    Route::get('settings', 'Admin\SettingController@index')->name('settings');
    Route::put('settingssave', 'Admin\SettingController@save')->name('settings.save');
    
    // esse comando gera todas as rotas do crud dos users
    Route::resource('users', 'Admin\UserController')->middleware('checkadmin');

    // esse comando gera todas as rotas do crud dos roles (perfis de usuario)
    Route::resource('roles', 'Admin\RoleController')->middleware('checkadmin');

    // esse comando gera todas as rotas do crud das permissoes 
    Route::resource('abilities', 'Admin\AbilityController')->middleware('checkadmin');

}); 



//prefixo dre
Route::prefix('dre')->group(function(){

    // esse comando gera todas as rotas do crud das filiais
    Route::resource('companies', 'dre\CompanyController')->middleware('can:Editar_Filiais');

    // esse comando gera todas as rotas do crud das contas
    Route::resource('accounts', 'dre\AccountController')->middleware('can:Editar_Contas');

    // esse comando gera todas as rotas do crud dos lancamentos
    Route::resource('entries', 'dre\EntryController')->middleware('can:Editar_Lanctos');
    Route::post('addEntry', 'dre\EntryController@addEntry')->name('addEntry');
    Route::post('updateEntry', 'dre\EntryController@updateEntry')->name('updateEntry');



}); 




Route::fallback('Site\PageController@index');

