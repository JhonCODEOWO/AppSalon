<?php

namespace Middlewares;

use Closure;
use Core\Auth;
use Core\Interfaces\MiddlewareInterface;
use Core\Response;
use Core\Session;
use Routes\Request;
use Override;

class AdminMiddleware implements MiddlewareInterface {
    #[Override]
    public function handle(Request $req, Closure $next): mixed
    {
        $result = false;
        $user = Auth::user();
        $errorResponse = [
            "message" => "Unauthorized"
        ];

        if($user->admin === true && $user != null) $result = true;
        
        if($req->isJsonRequest() && $result == false) 
            Response::json($errorResponse, 401);

        if(!$req->isJsonRequest() && $result == false) {
            Session::flash("error", "You should be authenticated and admin to interact.");
            redirectTo('/login');
        }

        return $next($req);
    }
}