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
        $lastname_list =  DB::table('profiles')->select('*')->get();
        $firstname_list =  DB::table('profiles')->select('profile_firstname')->get();
        $middlename_list =  DB::table('profiles')->select('profile_middlename')->get();
        $match = DB::table('profiles')->select('profile_lastname', 'profile_firstname')
        ->where('profile_lastname', '=', $request->lastname)
        ->where('profile_firstname', '=', $request->firstname)
        ->get();
        $array = array();

        if($match->isEmpty())
        {
            $soundex = DB::table('profiles')->select('*')
                ->where('profile_lastname', '==', soundex($request->lastname))
                ->get();
                // dd($soundex);
            foreach($lastname_list as $lastname)
            {
                // echo $lastname->profile_lastname;
                if (soundex($lastname->profile_lastname) == soundex($request->lastname)){
                //    return "Do you mean " . $lastname->profile_lastname . "?";
                    array_push($array, $lastname);


                }

            }
            return response()->json(['code' => '201', 'status' => 'successfull', 'message' => 'Do you mean this?', 'data' => $array]);
            // return response()->json(['code' => '201', 'status' => 'successfull', 'data' => 'zero result']);

        }
        else
        {

            return response()->json(['code' => '201', 'status' => 'successfull', 'data' => $match]);
        }
    }
}
