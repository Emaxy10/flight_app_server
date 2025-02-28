<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    //
    public function test(){
        $user = User::find(6);

        dd($user->getRolePermission());
    }
}
