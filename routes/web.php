<?php
use  Illuminate\Support\Facades\Redis;
use App\User;
use App\Events\Alert;
use App\Mail\pdfmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
$locale = Request::segment(1);

if (in_array($locale, Config::get('app.available_locales'))) {
    \App::setLocale($locale);
} else {
    $locale = null;
}

Route::group(['prefix' => $locale], function () {
    // App::setLocale($request->session()->get('lang'));
        Route::get('/mailing',function(Request $request){
            $language = $request->session()->get('lang');
            echo $language;
             //$user = Auth::user();
            //\Mail::to($user)->send(new pdfmail);
              //DB::table('news')->truncate();
            
        });
        Route::get('/events', function(){
        	
         	 event(new Alert());
        });
        Route::get('/', 'HomeController@index')->name('roots');

        Route::get('/me', function(){
        	//Redis::set('name','harbouj');
        	//return Redis::get('name');
        });
        Auth::routes();

        Route::get('/home', 'HomeController@index')->name('home');

        Route::get('/search', 'HomeController@search')->name('articles.search');
        Route::get('/files', 'HomeController@test')->name('articles.test');

        Route::get('/{id}', 'HomeController@show')->name('articles.show');

        Route::group(['middleware' => 'admin'], function () {
            Route::get('/users/all', 'UserController@all')->name('users.all');
            Route::get('/users/create', 'UserController@create')->name('users.create');
            Route::get('/users/{id}', 'UserController@show')->name('users.show');
            Route::post('/users/update/{id}', 'UserController@update')->name('users.update');
            Route::get('/users/destroy/{id}', 'UserController@destroy')->name('users.destroy');
            });

        Route::get('/admin/dashboard', 'AdminController@index')->name('dashboard');

        Route::get('/admin/indexing', 'AdminController@indexing')->name('indexing');

        Route::get('test/index', 'TestController@index');

        Route::get('/login', 'SessionController@create');
        Route::post('/login', 'SessionController@store')->name('login');
        //Route::post('/login', 'LoginController@index');
        Route::get('home/response', function () {
            return Response::make('Je suis la page', 404);
        });
});

