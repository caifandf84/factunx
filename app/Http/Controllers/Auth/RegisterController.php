<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller {
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|min:6|confirmed',
        ]);
    }

    protected function validatorContributenteAsignado(array $data) {
        return Validator::make($data, [
                    'rfc' => 'required',
                    'razon_social' => 'required',
                    'correo' => 'required',
        ]);
    }

    protected function validatorContributenteNew(array $data) {
        return Validator::make($data, [
                    'rfc' => ['required', 'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[A-Z|\d]{3})$/'],
                    'razon_social' => 'required',
                    'pais' => 'required|not_in:0',
                    'estado' => 'required',
                    'municipio' => 'required',
                    'colonia' => 'required',
                    'codigo_postal' => 'required|not_in:0',
                    'calle' => 'required',
                    'num_ext' => 'required',
                    'correo_electronico' => 'required|email',
                    'cel' => 'nullable|numeric',
                    'archivo_cer' => 'required|max:2048',
                    'archivo_key' => 'required|max:2048',
                    'sello_contrasenia' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {
        $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
        ]);
        $environment = \App::environment();
        if ($environment != 'Production') {
            $uCont = new \App\UsuarioContribuyente();
            $uCont->guardar($user->id, 1);
        }
        return $user;
    }

    protected function createTmp(array $data, $idContribuyente) {
        $userSinAct = new \App\UserSinActivar();
        $user = $userSinAct->guardar($data['name'], $data['email'], $data['password'], $idContribuyente);
        return $user;
    }

}
