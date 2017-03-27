<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 12:04 AM
 */

class OperadorTableSeeder extends Seeder{
    public function run()
    {
        DB::table('Operador')->delete();

        DB::table('Operador')->insert(
            array(
                array('op_nombre' => 'Ransa'),
                array('op_nombre' => 'Tramarsa'),
                array('op_nombre' => 'Ransa-Tramarsa'),
                array('op_nombre' => 'SLI')
            ));
    }

}
