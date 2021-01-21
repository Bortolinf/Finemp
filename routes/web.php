<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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




// reset password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

// post do reset password
Route::post('/forgot-password', function (Request $request) {
    //echo "oi, esto no forgot";
    //exit();
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');


// form do password reset
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');


// 18/12/2020
// post do form c/nova senha (enviado por e-mail)
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) use ($request) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
            $user->setRememberToken(Str::random(60));
            event(new PasswordReset($user));
        }
    );
    return $status == Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');









//prefixo painel
Route::prefix('painel')->group(function(){
    Route::get('/', 'Admin\HomeController@index')->name('admin');
    
    Route::get('login', 'Admin\Auth\LoginController@index')->name('login');
    Route::post('login', 'Admin\Auth\LoginController@authenticate');
    Route::post('logout', 'Admin\Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Admin\TenantController@index')->name('register');
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
    // esse comando gera todas as rotas do crud das configuracoes 
    Route::resource('settings', 'dre\SettingController')->middleware('checkadmin');
    
    // esse comando gera todas as rotas do crud das filiais
    Route::resource('companies', 'dre\CompanyController')->middleware('can:Editar_Filiais');

    // esse comando gera todas as rotas do crud das contas
    Route::resource('accounts', 'dre\AccountController')->middleware('can:Editar_Contas');
    Route::get('accounts.autocreate', 'dre\AccountController@autoCreate')->middleware('can:Editar_Contas')->name('accounts.autocreate');

    // esse comando gera todas as rotas do crud dos lancamentos
    Route::resource('entries', 'dre\EntryController')->middleware('can:Editar_Lanctos');
    Route::post('addEntry', 'dre\EntryController@addEntry')->name('addEntry');
    Route::post('updateEntry', 'dre\EntryController@updateEntry')->name('updateEntry');

    Route::get('dre_simples', 'dre\ReportsController@dresimples')->name('dre_simples');

    Route::get('dre_mes', 'dre\ReportsController@dreDoMes')->name('dre_mes');
    Route::post('dre_mes', 'dre\ReportsController@dreDoMes')->name('dre_mes');

    Route::get('dre_comparativo', 'dre\ReportsController@dreComparativo')->name('dre_comparativo');
    Route::post('dre_comparativo', 'dre\ReportsController@dreComparativo')->name('dre_comparativo');

}); 




Route::fallback('Site\PageController@index');

