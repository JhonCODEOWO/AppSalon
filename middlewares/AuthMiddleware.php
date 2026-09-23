<?php

namespace Middlewares;

use Closure;
use Core\Auth;
use Core\Interfaces\MiddlewareInterface;
use Core\Session;
use Routes\Request;

class AuthMiddleware implements MiddlewareInterface{
    public function handle(Request $req, Closure $next) : mixed
    {
        if(!Auth::authenticated()) {
            Session::flash("error", "You should be login to access.");
            redirectTo('/login');
        }
        return $next($req);
    }
}