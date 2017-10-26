<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    public function test() {
        $users = DB::table('users')->get();

        echo "<pre>"; print_r($users); exit;
    }
}
