<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        DB::table('commentaire')->insert([

            'commentaire' => $header['commentaire'][0],
            'id_event' => $header['idevent'][0],
            'id_user' => $header['iduser'][0],
            

        ]);

        return "{'code' : 'OK'}";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comments = DB::table('commentaire')->where('id_commentaire','=',$id)->get();
        return $comments;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        DB::table('commentaire')->where('id_commentaire', $id)->update([

            'commentaire' => $header['commentaire'][0],
            'id_event' => $header['idevent'][0],
            'id_user' => $header['iduser'][0],

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
        DB::table('commentaire')->where('id_commentaire', '=', $id)->delete();

        return "{'code' : 'OK'}";
    }
}
