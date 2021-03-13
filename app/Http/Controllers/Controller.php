<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    //
    public function checker(Request $request)
    {

        $data = $request->all();
        $list =  DB::table('profiles')->select('*')->get();
        $match = DB::table('profiles')
        ->select('profile_lastname', 'profile_firstname')
        ->where('profile_lastname', '=', $request->lastname)
        ->where('profile_firstname', '=', $request->firstname)
        ->where('profile_middlename', '=', $request->middlename)
        ->get();
        $array = array();
        $array2 = array();
        if($match->isEmpty())
        {
            foreach($list as $name)
            {
                if (soundex($name->profile_lastname) == soundex($request->lastname))
                {
                //    return "Do you mean " . $lastname->profile_lastname . "?";
                    array_push($array, $name);
                }
                if (soundex($name->profile_firstname) == soundex($request->firstname))
                {
                    //    return "Do you mean " . $lastname->profile_lastname . "?";
                        array_push($array2, $name);
                }

            }
            return response()->json(['code' => '201', 'status' => 'successfull', 'message' => 'Do you mean this?', 'lastname' => $array, 'firstname' => $array2]);

        }
        else
        {

            return response()->json(['code' => '201', 'status' => 'successfull', 'data' => $match]);
        }
    }
}
