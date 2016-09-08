<?php

namespace App\Http\Controllers\Mis;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{

    protected $viewPath = 'login';
    public function __construct(Request $request){
//        $this->middleware('auth');
//        if (!Auth::check()) {
//            // The user is logged in...
//            return redirect('auth/login');
//        }

    }

    /**
     * @brief 返回试图界面
     * @param string $path
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function view($path = '', Array $data = []) {
        return view(sprintf($this->viewPath, $path), $data);
    }
}
