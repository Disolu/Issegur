<?php
/**
 * Created by PhpStorm.
 * User: disolu
 * Date: 13/06/15
 * Time: 04:30 AM
 */

class RansaUserTableSeeder extends Seeder{

    public function run()
    {
        DB::table('RansaUsuario')->delete();

        DB::table('RansaUsuario')->insert(array(array('ruser_username' => 'test','ruser_password' => Hash::make('test')),
                                            array('ruser_username' => 'test1','ruser_password' => Hash::make('test1')),
                                           ));
    }
}