<?php
namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\ParameterBag;

class TestMiddleware {

    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
//        $data = $request->get('data');
//        $data = $data+2;
//        $request->offsetSet('data',$data);
//        $request->query =  new ParameterBag(['data'=> $data]);
        if (empty( $request->all() )) {
//            return view('welcome');
        };

        return $next($request);
    }
}
