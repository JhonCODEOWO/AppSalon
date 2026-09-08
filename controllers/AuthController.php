<?php

namespace Controllers;

use Core\Auth;
use Core\Database;
use Core\Errors;
use Core\JustArray\JustArray;
use Core\Mailer\Mailer;
use Core\Validator;
use Exception;
use Models\User;
use Routes\Request;

class AuthController {
    public function login(Request $req){
        view(
            'Auth/login', 
            [],
            'layouts/main'
        );
    }

    public function loginUser(Request $req){
        $user = null;
        $body = $req->getBody();
        $validator = new Validator(
            $body,
            [
                "email" => "required|email|exists:users,email",
                "password" => "required|minLength:8",
            ]
        );

        $errors = $validator->validate();

        if(!$errors->hasErrors()){
            $user = Auth::attempt(
                JustArray::find($body, 'email'),
                JustArray::find($body, 'password')
            );
        }

        if(!$this->isUserConfirmed($user) && $user != null){
            $errors->add(
                "The email associated to this account is not confirmed.",
                "email"
            );
        }

        //Authenticate and redirect
        if(!$errors->hasErrors()) {

            redirectTo('/');
        };

        view(
            'Auth/login',
            [
                "errors" => $errors,
                "old" => $body,
            ],
            "layouts/main"
        );
    }

    public function logout(Request $req){

    }

    public function forgotPassword(){
        view(
            "Auth/forgotPassword",
            [],
            "layouts/main"
        );
    }

    public function sentMail(Request $req){

    }

    public function reset(Request $req){

    }

    public function resetPassword(Request $req){

    }

    public function create(){
        view(
            "Auth/createAccount",
            [],
            "layouts/main"
        );
    }

    /**
     * Create a account action
     */
    public function store(Request $req){
        $body = $req->getBody([
            "admin" => 0,
            "confirmed" => 0
        ]);

        $validator = new Validator(
            $body,
            [
                "nombre" => "required",
                "apellido" => "required",
                "telefono" => "required",
                "email" => "required|unique:users,email",
                "password" => "required|minLength:8",
                "password_confirmation" => "required|minLength:8|confirmed:password",
            ]
        );

        $errorsBag = $validator->validate();
        
        if($errorsBag->hasErrors()){
            view(
                "Auth/createAccount",
                [
                    "errors" => $errorsBag,
                    "old" => $body,
                ],
                "layouts/main"
            );
            exit;
        };
        
        $user = new User([
            "name" => 
                JustArray::find($body, 'nombre'),
            "last_name" => 
                JustArray::find($body, 'apellido'),
            "phone_number" => 
                JustArray::find($body, 'telefono'),
            "password" => 
                password_hash(JustArray::find($body, 'password'), PASSWORD_BCRYPT),
            "admin" => 
                JustArray::find($body, 'admin'),
            "email" => 
                JustArray::find($body, 'email'),
            "token" =>
                uniqid()
        ]);

        $mailer = new Mailer();
        $mailer->subject("Account confirmation");
        $mailer->to([$user->email]);
        $mailer->useTemplate(
            '/confirm_account',
            [
                "username" => $user->name,
                "token" => $user->token,
            ]
        );

        $mailer->send();
        $user->save();

        redirectTo('/account-created');
    }

    public function accountCreated(){
        view('Auth/accountCreatedConfirm', []);
    }

    public function confirm(Request $req){
        $user = null;
        $body = [
            "token" => safe($req->getUrlParamValue('token')),
        ];

        $validator = new Validator($body, [
            "token" => 'required|minLength:13',
        ]);
        $errors = $validator->validate();

        if($errors->hasErrors()){
            view(
                'Auth/confirmedAccount', 
                [
                    "errors" => $errors
                ],
                'layouts/main'
            );
            exit;
        }

        $user = User::where('token', JustArray::find($body, 'token'));

        if($user === null) $errors->add("Not exists a user to confirm with the requested token", "token");

        if(!$errors->hasErrors()){
            $user->update([
                "confirmed" => 1,
                "token" => null,
            ]);
        }
        
        view(
            'Auth/confirmedAccount', 
            [
                "errors" => $errors
            ],
            'layouts/main'
        );
    }

    /**
     *  Checks if a instance of user has true value in confirmed property.
     *
     * @param ?User $user
     * @return bool
     */
    private function isUserConfirmed(?User $user): bool{
        if($user === null) return false;

        return ($user->confirmed === true);
    }
}