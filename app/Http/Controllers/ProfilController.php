<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
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
        $user = $request->user;

        $nom = $user['nom'];
        $prenom = $user['prenom'];
        $sexe = $user['sexe'];
        $mail = $user['mail'];
        $telephone = $user['telephone'];
        $birthday = $request->birthday;
        $description = $user['description'];
        $ville = $user['ville'];
        //$cp = $user['cp'];
        $country = $user['country'];

        $sports = $request->sports;
        $iduser = $request->iduser;

        DB::table('users')
            ->where('id_user', $iduser)
            ->update([

                'nom' => $nom,
                'prenom' => $prenom,
                'sexe' => $sexe,
                'telephone' => $telephone,
                'mail' => $mail,
                'description' => $description,
                'birthday' => $birthday,
                'ville' => $ville,
                'country' => $country

            ]);


        DB::table('sport_user')->where('id_user', '=', $iduser)->delete();

        foreach ($sports as $key => $sport) {
            

            $id_sport = DB::table('sport')
                    ->select('id_sport')
                    ->where('sport','=',$sport)->get();

            $id_sport = $id_sport[0]->id_sport;


            DB::table('sport_user')->insert([

                'id_sport' => $id_sport,
                'id_user' => $iduser,
                
            ]);

        }

        $return['code'] = "Informations mises a jour";

        return $return;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $tab_rendu = [];

        $user['user'] = DB::table('users')
                    ->where('id_user',$id)->get();

        $sports = DB::table('sport_user')->join('sport', 'sport.id_sport', '=', 'sport_user.id_sport')
                    ->select('sport')
                    ->where('id_user',$id)->get();

        foreach ($sports as $key => $sport) {
            $tab_rendu[] = $sport->sport;
        }

        $user['sport'] = $tab_rendu;

        return $user;
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
        //
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


    public function toutesvilles(){

        $tab_rendu_villes = [];
        $tab_rendu_cp = [];
        $tab_rendu_sports = [];


        $villes = DB::table('villes_france_free')->select('ville_nom_simple')->orderBy('ville_nom_simple','asc')->get();
        $sports = DB::table('sport')->select('sport')->orderBy('sport','asc')->get();
        $cps = DB::table('villes_france_free')->select('ville_code_postal')->get();

        foreach ($villes as $key => $ville) {
            $tab_rendu_villes[] = $ville->ville_nom_simple;
        }

        foreach ($cps as $key => $cp) {
            
            $tab_rendu_cp[] = $cp->ville_code_postal;
        }

        foreach ($sports as $key => $sport) {
            
            $tab_rendu_sports[] = $sport->sport;
        }

        $return['villes'] = $tab_rendu_villes;
        $return['cps'] = $tab_rendu_cp;
        $return['sports'] = $tab_rendu_sports;


        return $return;
    }
}
