<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function connexion(Request $request)
    {
        $pseudo = $request->pseudo;
        $mdp = $request->password;

        $user = DB::table('users')->where([

        	['pseudo', '=', $pseudo],
        	['mdp', '=', $mdp]

        ])->get();

        $return = [];

        if(sizeof($user) != 0){

        	$return['code'] = "OK";
        	$return['user'] = $user;

        }else{

        	$return['code'] = "Combo Pseudo/Mdp inconnu";

        }

        return $return;
    }

    public function inscription(Request $request)
    {
        $pseudo = $request->pseudo;
        $mdp = $request->password;
        $mail = $request->email;

        $user = DB::table('users')->insertGetId(
				    ['mail' => $mail, 'mdp' => $mdp, 'pseudo' => $pseudo, 'level' => 1]
				);

        $return = [];

        if($user != 0){

        	$return['code'] = "OK";
        	$return['user'] = $user;

        }else{

        	$return['code'] = "Inscription impossible";

        }

        return $return;
    }

    public function suppression(Request $request)
    {
        $iduser = $request->iduser;

        DB::table('users')->where('id_user', '=', $iduser)->delete();

        $return['code'] = "OK";
           

        return $return;

    }
}