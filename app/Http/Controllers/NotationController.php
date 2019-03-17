<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class NotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $header = $request->header();

        DB::table('notation')->insert([

            'note' => $request->note,
            'id_receveur' => $request->receveur,
            'id_emetteur' => $request->emetteur,
            'message' => $request->message,
            

        ]);

        return "{'code' : 'OK'}";

    }

    /**
     * Display the specified resource.
     * api/notation/{id_user}
     * @param  int  $id_user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }
    

    /**
     * Display the specified resource.
     * api/notation/{id_user}
     * @param  int  $id_user
     * @return \Illuminate\Http\Response
     */
    public function mynotes($id)
    {
        
        $notationsM = DB::table('notation')

        ->join('users','users.id_user','=','notation.id_receveur')
        ->select('notation.*', 'users.*')
        ->where('id_receveur',$id)->get();


        $notationsG = DB::table('notation')

        ->join('users','users.id_user','=','notation.id_emetteur')
        ->select('notation.*', 'users.*')
        ->where('id_emetteur',$id)->get();

        $retour = [];
        $retour['mynotes'] = $notationsM;
        $retour['giftnotes'] = $notationsG;

        return $retour;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $header = $request->header();

        DB::table('notation')->where('id_notation', $id)->update([

            'note' => $request->note,
            'id_receveur' => $request->receveur,
            'id_emetteur' => $request->emetteur,
            'message' => $request->message,

        ]);

        return "{'code' : 'OK'}";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
