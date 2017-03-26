<?php
/**
 * Created by PhpStorm.
 * User: disolu
 * Date: 13/06/15
 * Time: 04:30 AM
 */

class UserTableSeeder extends Seeder{

    public function run()
    {
        DB::table('Usuario')->delete();

        DB::table('Usuario')->insert(array(array('rol_id' => 1,'username' => 'rberrospi','password' => Hash::make('disolu12#')),
                                            array('rol_id' => 2,'username' => 'cvereau','password' => Hash::make('test12#')),
                                           ));
    }
}
