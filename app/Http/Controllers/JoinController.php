<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JoinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leave(Request $request)
    {
        $idevent = $request->idevent;
        $iduser = $request->iduser;

        $event = DB::table('event')->where('id_event','=',$idevent)->get();

        $date = $event[0]->date;

        if($date < date ('Y-m-d')){

            return "{'code' : 'Cet événement est déjà passé'}";

        }else{

            DB::table('rejoint')->where([

                ['id_event', '=', $idevent],
                ['id_user', '=', $iduser]

            ])->delete();

            $users['users'] = DB::table('rejoint')
                            ->select('users.*')
                            ->join('users','users.id_user','=','rejoint.id_user')
                            ->where('rejoint.id_event','=',$idevent)->get();

            return $users;

        }

        

        return "{'code' : 'OK'}";
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

        $idevent = $request->idevent;
        $iduser = $request->iduser;

        $event = DB::table('event')->where('id_event','=',$idevent)->get();

        $date = $event[0]->date;
        $nbmax = $event[0]->nb_max;

        //On teste la date
        if($date < date ('Y-m-d')){

            $return['code'] = "Cet événement est déjà passé";

            return $return;

        //Si date OK on test si on est déjà inscrit
        }else{

            $eventpart = DB::table('rejoint')->where([

                ['id_event', $idevent], 
                ['id_user', $iduser]

            ])->get();

            $bool = sizeof($eventpart);

            if($bool){

                $return['code'] = "Vous êtes déjà inscrit à cet événement";

                return $return;

            //Si on est pas inscrit
            }else{

                $nbparticipant = DB::table('rejoint')->where('id_event', $idevent)->get();
                $intparticipant = sizeof($nbparticipant);

                //Si ya encore de la place
                if($intparticipant < $nbmax){

                    DB::table('rejoint')->insert([

                        'id_event' => $idevent,
                        'id_user' => $iduser,
                        
                    ]);

                    $users['users'] = DB::table('rejoint')
                                    ->select('users.*')
                                    ->join('users','users.id_user','=','rejoint.id_user')
                                    ->where('rejoint.id_event','=',$idevent)->get();

                    $users['code'] = "OK";

                    return $users;


                //Sinon c'est rempli
                }else{
                    
                    $return['code'] = "Il n'y a plus de place pour cet événement, regardez en un autre :)";
                    return $return;
                }
                

                return "{'code' : 'OK'}";
            }

        }

        
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
        //
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
}
