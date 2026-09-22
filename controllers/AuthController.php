<?php

namespace Controllers;

use Core\Auth;
use Core\Database;
use Core\Errors;
use Core\JustArray\JustArray;
use Core\Mailer\Mailer;
use Core\Session;
use Core\Validator;
use Exception;
use Models\User;
use Routes\Request;

class AuthController {
    public function login(Request $req){
        $confirmedMessage = Session::getPrevFlashData('success') ?? null;
        view(
            'Auth/login', 
            [
                "confirmedMessage" => $confirmedMessage,
            ],
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
                "email",
                true
            );
        }

        if($errors->hasErrors()) redirectTo("/login");
        
        //Authenticate and redirect
        Auth::login(
            [
                "id" => $user->id,
                "name" => $user->name,
            ]
        );

        ($user->admin == true)
            ?
                redirectTo('/panel')
            :
                redirectTo('/');
    }

    public function logout(Request $req){

    }

    public function forgotPassword(){
        $successMessage = Session::getPrevFlashData("success") ?? null;
        view(
            "Auth/forgotPassword",
            [
                "successMessage" => $successMessage,
            ],
            "layouts/main"
        );
    }

    public function sendResetMail(Request $req){
        $body = $req->getBody();

        $validator = new Validator(
            $body,
            [
                "email" => 'required|exists:users,email|email'
            ]
        );

        $errors = $validator->validate();

        if($errors->hasErrors()) redirectTo('/forgot-password');

        $user = User::where('email', JustArray::find($body, 'email'));

        if(!$this->isUserConfirmed($user) || $user === null) 
            $errors->add("It seems a account with this email is not confirmed or not exists.", "email", true);

        if($errors->hasErrors()) redirectTo('forgot-password');

        $token = uniqid();

        $mailer = new Mailer();
        $mailer->from();
        $mailer->to([$user->email]);
        $mailer->subject('Reset password');
        $mailer->useTemplate(
            'missing_password',
            [
                "token" => $token,
                "host" => $_ENV['APP_HOST'],
            ]
        );

        try {
            $mailer->send();
            $user->update(["token" => $token]);
            Session::flash("success", "A mail has already sent with instructions to continue");
            redirectTo('/forgot-password');
        } catch (Exception $ex) {
            $errors->add("A error occurred while we tried to send the mail, try again.", "mail-status");
            redirectTo("/forgot-password");
        }
    }

    public function reset(Request $req){
        $token = $req->getUrlParamValue('token') ?? "";
        view(
            "Auth/resetPassword",
            [
                "token" => $token,
            ],
            "layouts/main"
        );
    }

    public function resetPassword(Request $req){
        $body = $req->getBody(["token" => ""]);

        $validator = new Validator(
            $body,
            [
                "password" => 
                    "required|minLength:8",
                "password_confirmation" =>
                    "required|minLength:8|confirmed:password",
                "token" => "required|exists:users,token"
            ]
        );

        $errors = $validator->validate();

        //TODO: Redirect to get route with errors in session flash data.
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
        
        if($errorsBag->hasErrors()) redirectTo("/create-account");
        
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
        $token = JustArray::find($body, "token");

        view(
            "Auth/confirmAccount",
            [
                "token" => $token,
            ],
            "layouts/main"
        );
    }

    public function confirmAccount(Request $req){
        $body = $req->getBody();
        $token = JustArray::find($body, "token");

        $validator = new Validator($body, [
            "token" => 'required|minLength:13',
        ]);
        $errors = $validator->validate();

        if($errors->hasErrors()) redirectTo("/confirm-account/$token");

        $user = User::where('token', $token);

        if($user === null) $errors->add("Not exists a user to confirm with the requested token", "token", true);

        if($errors->hasErrors()) redirectTo("/confirm-account/$token");

        $user->update([
            "confirmed" => 1,
            "token" => null,
        ]);

        Session::flash('success', "Account confirmed successfully, now you can login.");
        redirectTo('/login');
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