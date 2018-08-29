<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // function __construct(){
    // 	$this->kiemTraDangNhap();
    // 	// if(Auth::check()){
    // 	// 	view()->share('user_login', Auth::user());
    // 	// }
    // }

    // function kiemTraDangNhap(){
    // 	if(Auth::check()){
    // 		View()->share('user_login', Auth::user());
    // 	}
    // }
}