<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Abonnement;
use App\Keyword;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/users/all';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'cin' => 'required|max:255|unique:users',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'cin' => $data['cin'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'tel' => $data['tel'],
            'address' => $data['address'],
        ]);

        $data['keywords'] = explode( ',', $data['keywords'][0] );
        $keywords = [];
        foreach ($data['keywords'] as $val)
        {
            $keyword = Keyword::firstOrCreate(['name' => $val]);
            array_push($keywords, $keyword->id);
        }

        $user->keywords()->attach($keywords === null ? [] : $keywords);

        Abonnement::insert([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'user_id' => $user->id,
            ]);
    }
}
