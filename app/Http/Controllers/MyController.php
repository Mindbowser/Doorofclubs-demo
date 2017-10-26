<?php

namespace App\Http\Controllers;

//use DB;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Users;

class MyController extends Controller
{
    public function index() {
        //$users = DB::table('users')->get();
        //echo "<pre>"; print_r($users); exit;
        
        $users = new Users();
        return $users->test();
    }
}
