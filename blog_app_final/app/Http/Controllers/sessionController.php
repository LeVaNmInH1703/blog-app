<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class sessionController extends Controller
{
    //
    public function set($name,$value){
        Log::info("$value");
        session([$name=>$value]);
    }
}
