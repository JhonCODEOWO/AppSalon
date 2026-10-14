<?php

namespace Middlewares;

use Closure;
use Core\Auth;
use Core\Interfaces\MiddlewareInterface;
use Core\Response;
use Core\Session;
use Routes\Request;

class AuthMiddleware implements MiddlewareInterface{
    public function handle(Request $req, Closure $next) : mixed
    {
        if(!Auth::authenticated()) {

            if(str_contains($req->getAccept(), "application/json")) {
                Response::json([
                    "status" => 401,
                    "message" => "Unauthorized"
                ], 401);
            }

            Session::flash("error", "You should be login to access.");
            redirectTo('/login');
        }
        return $next($req);
    }
}