<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EventController extends Controller
{

    /**
     * Retourne tous les events en fonction des coordonnées envoyées
     * Lat1 - Lat2 / Lng1 - Lng2 (1 en haut à gauche : 2 en bas à droite)
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) //Ajouter fonction de temps
    {
        
        $where = [];
        $whereIN = [];

        if($request->search != ""){

            //Longitude 0,01 Nancy <---> Lat 0,05
            $text = $request->search;

            $codepostal = [];
            $sport_ville = [];
            $heuresearch = "";

            preg_match('/[0-9]{5}/', $text, $codepostal); //Récupère un code postal
            preg_match_all('/[a-zA-Z]\S*/', $text, $sport_ville); //Récupère une ville ou un sport
            preg_match('/[0-9]{1,2}(h|:)[0-9]{0,2}/', $text, $heuresearch); //Récupère un code postal


            if(sizeof($codepostal) != 0){ // On récupère la ville et sa position
                
                $codeP = ''.$codepostal[0];
                $ville = DB::table('villes_france_free')->where('ville_code_postal','=',$codeP)->get();

                if(sizeof($ville) != 0){

                    $where[] = ['lat', '>=' , $ville[0]->ville_latitude_deg-0.025];
                    $where[] = ['lat', '<=' , $ville[0]->ville_latitude_deg+0.025];
                    $where[] = ['lng', '>=' , $ville[0]->ville_longitude_deg-0.05];
                    $where[] = ['lng', '<=' , $ville[0]->ville_longitude_deg+0.05];

                }else{

                    $where[] = ['lat', '>=' , $request->lat1];
                    $where[] = ['lat', '<=' , $request->lat2];
                    $where[] = ['lng', '>=' , $request->lng1];
                    $where[] = ['lng', '<=' , $request->lng2];
                }

            }else if(sizeof($sport_ville) != 0){ // On récupère les textes pour tester villes et sport

                $ville = DB::table('villes_france_free')
                    ->whereIn('ville_nom_simple',$sport_ville[0])
                    ->get();

                if(sizeof($ville) != 0){

                    $where[] = ['lat', '>=' , $ville[0]->ville_latitude_deg-0.025];
                    $where[] = ['lat', '<=' , $ville[0]->ville_latitude_deg+0.025];
                    $where[] = ['lng', '>=' , $ville[0]->ville_longitude_deg-0.05];
                    $where[] = ['lng', '<=' , $ville[0]->ville_longitude_deg+0.05];

                }else{

                    $where[] = ['lat', '>=' , $request->lat1];
                    $where[] = ['lat', '<=' , $request->lat2];
                    $where[] = ['lng', '>=' , $request->lng1];
                    $where[] = ['lng', '<=' , $request->lng2];
                }
                
            }else{

                $where[] = ['lat', '>=' , $request->lat1];
                $where[] = ['lat', '<=' , $request->lat2];
                $where[] = ['lng', '>=' , $request->lng1];
                $where[] = ['lng', '<=' , $request->lng2];
                
            }

            //Gestion de l'heure
            if(sizeof($heuresearch) != 0){

                $heuresearch[0] = preg_replace('/h/', ':', $heuresearch[0]);

                $heuresearch[0] = preg_replace('/:$/', ':00', $heuresearch[0]);

                $where[] = ['time', '>=' , $heuresearch[0]];
                
            }

            if(sizeof($sport_ville) != 0){ //On teste les sports


                $sportspossible = DB::table('event')->select('sport')->distinct()->whereIn('sport', $sport_ville[0])->get();

                if(sizeof($sportspossible) != 0){

                    $lesports = [];

                    foreach ($sportspossible as $key => $value) {
                        
                        $lesports[] = $value->sport;

                    }

                    $whereIN = $lesports;

                    $events['events'] = DB::table('event')->where($where)->whereIn('sport',$whereIN)->get();

                    return $events;
                }

            }

            $events['events'] = DB::table('event')->where($where)->get();

            return $events;
            
        }

        if($request->dateevent != ""){

            $where[] = ['date' , $request->dateevent];

        }


        if($request->heure != ""){

            $where[] = ['time', '>=' , $request->heure];

        }

        $where[] = ['lat', '>=' , $request->lat1];
        $where[] = ['lat', '<=' , $request->lat2];
        $where[] = ['lng', '>=' , $request->lng1];
        $where[] = ['lng', '<=' , $request->lng2];

        $today = date ('Y-m-d');

        $where[] = ['date', '>', $today];

        if($request->sport != ""){

            $events['events'] = DB::table('event')->where($where)->whereIn('sport',$request->sport)->orderBy('date', 'asc')->get();

            return $events;

        }

        $events = DB::table('event')->where($where)->orderBy('date', 'asc')->get();
        

        $eventsreturn['events'] = $events;

        return $eventsreturn;
    }

    /**
     * Récûpère tous nos events
     *
     * @return \Illuminate\Http\Response
     */

    public function myevents($id)
    {

        $today = date ('Y-m-d');


        $passedevents = DB::table('event')->where([

            ['createur' , $id],
            ['date' , '<', $today]

        ])->orderBy('date', 'asc')->get();


        $futurevents = DB::table('event')->where([

            ['createur' , $id],
            ['date' , '>=', $today]

        ])->orderBy('date', 'asc')->get();


        $events['passed'] = $passedevents;
        $events['futur'] = $futurevents;

        return $events;
    }


    /**
     * Récûpère tous nos events
     *
     * @return \Illuminate\Http\Response
     */

    public function joinedevents($id)
    {

        $today[] = date ('Y-m-d');

        $joinedevents = DB::table('rejoint')->where('id_user' , $id)->get();

        $idevents = [];

        foreach ($joinedevents as $key => $value) {
            $idevents[] = $value->id_event;
        }

        $passedevents = DB::table('event')

        ->wherein('id_event' , $idevents)
        ->where('date' , '<', $today)
        ->orderBy('date', 'asc')
        ->get();

        $futurevents = DB::table('event')

        ->wherein('id_event' , $idevents)
        ->where('date' , '>=', $today)
        ->orderBy('date', 'asc')
        ->get();

        $events['passed'] = $passedevents;
        $events['futur'] = $futurevents;

        return $events;
    }


    /**
     * Créer un événement
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


        if($request->dateevent >= date ('Y-m-d')){

            $idevent = DB::table('event')->insertGetId([

                'sport' => $request->sport,
                'date' => $request->dateevent,
                'time' => $request->heure,
                'description' => $request->desc,
                'titre' => $request->titre,
                'createur' => $request->createur,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'nb_max' => $request->nbmax

            ]);



            DB::table('rejoint')->insert([

                'id_event' => $idevent,
                'id_user' => $request->createur,
                
            ]);

            $return['code'] = "OK";
            $return['id'] = $idevent;

            return $return;

        }else{

            $return['code'] = "Il faut voir vers le futur";
            return $return;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        //Récuperer les utilisateurs qui y participent
        $events = DB::table('event')
        ->join('users','users.id_user','=','event.createur')
        ->select('event.*', 'users.id_user', 'users.nom', 'users.prenom')
        ->where('id_event','=',$id)->get();

        $comments = DB::table('commentaire')->where('id_event','=',$id)->get();
        $users = DB::table('rejoint')
        ->select('users.*')
        ->join('users','users.id_user','=','rejoint.id_user')
        ->where('rejoint.id_event','=',$id)->get();

        $retour = [];
        $retour['events'] = $events;
        $retour['comments'] = $comments;
        $retour['users'] = $users;

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

        DB::table('event')->where('id_event', $id)->update([

            'id_sport' => $request->sport,
            'date' => $request->dateevent,
            'heure' => $request->heure,
            'minute' => $request->minute,
            'description' => $request->desc,
            'titre' => $request->titre,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'nb_max' => $request->nbmax

        ]);

        return "{'code' : 'OK'}";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        

        $event = DB::table('event')->where('id_event','=',$id)->get();

        $user = $event[0]->createur;

        if($user == $request->iduser){

            DB::table('event')->where('id_event', '=', $id)->delete();
            DB::table('rejoint')->where('id_event', '=', $id)->delete();

            //Création de la notif

        }else{

            return "{'code' : 'Cet événement ne vous appartient pas !'}";
        }

        return "{'code' : 'OK'}";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaveevent(Request $request, $id)
    {

        DB::table('rejoint')->where([


            ['id_event', '=' , $id],
            ['id_user' , '=' , $request->iduser]


        ])->delete();

        return "OK";

    }
}
