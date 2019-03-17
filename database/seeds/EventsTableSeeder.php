<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	/*for ($i=0; $i < 50; $i++) { 
    	
	       	DB::table('event')->insert([

	       		'sport' => str_random(10),
	       		'date' => '2019'.'/'.mt_rand(1, 12).'/'.mt_rand(1, 29),
                'time' => mt_rand(1, 24).':'.mt_rand(0,59),
	            'description' => str_random(20),
	            'titre' => str_random(10),
	            'createur' => mt_rand(1, 50),
	            'lat' => mt_rand(48, 49)+mt_rand(55, 75)/100,
	            'lng' => 6+mt_rand(1, 30)/100,
	            'nb_max' => mt_rand(2, 15)

	        ]);
       	}

        for ($i=0; $i < 100; $i++) { 

            DB::table('commentaire')->insert([
                
                'commentaire' => str_random(100),
                'id_event' => mt_rand(1, 50),
                'id_user' => mt_rand(1, 50),

            ]);

        }

        for ($i=0; $i < 100; $i++) { 

            DB::table('notation')->insert([
                
                'note' => mt_rand(0, 5),
                'id_receveur' => mt_rand(1, 50),
                'id_emetteur' => mt_rand(1, 50),
                'message' => str_random(100)
            ]);

        }


        for ($i=0; $i < 200; $i++) { 

            DB::table('rejoint')->insert([
                
                'id_event' => mt_rand(1, 50),
                'id_user' => mt_rand(1, 50),

            ]);

        }*/

        for ($i=1; $i <= 50; $i++) { 

            DB::table('users')->insert([
                
                'nom' => str_random(10),
                'prenom' => str_random(10),
                'pseudo' => str_random(15),
                'sexe' => mt_rand(1, 2),
                'mail' => str_random(10).'@gmail.com',
                'birthday' => mt_rand(1980, 2000).'/'.mt_rand(1, 12).'/'.mt_rand(1, 29),
                'level' => mt_rand(1, 50),
                'mdp' => '12345',
                'telephone' => str_random(10),
                'country' => str_random(10),
                'description' => str_random(15),

            ]);

        }
    }
}
