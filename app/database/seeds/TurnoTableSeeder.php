<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 12:04 AM
 */

class TurnoTableSeeder extends Seeder{
    public function run()
    {
        DB::table('Turno')->delete();

        DB::table('Turno')->insert(
            array(
                array('turno_dia' => 'Lunes','turno_hora_inicio' => '09:00', 'turno_hora_fin' => '11:00'),
                array('turno_dia' => 'Lunes','turno_hora_inicio' => '12:00', 'turno_hora_fin' => '14:00'),
                array('turno_dia' => 'Martes','turno_hora_inicio' => '09:00', 'turno_hora_fin' => '11:00'),
                array('turno_dia' => 'Martes','turno_hora_inicio' => '12:00', 'turno_hora_fin' => '14:00'),
                array('turno_dia' => 'Miércoles','turno_hora_inicio' => '09:00', 'turno_hora_fin' => '11:00'),
                array('turno_dia' => 'Miércoles','turno_hora_inicio' => '14:00', 'turno_hora_fin' => '16:00'),
                array('turno_dia' => 'Jueves','turno_hora_inicio' => '09:00', 'turno_hora_fin' => '11:00'),
                array('turno_dia' => 'Jueves','turno_hora_inicio' => '12:00', 'turno_hora_fin' => '14:00'),
                array('turno_dia' => 'Viernes','turno_hora_inicio' => '09:00', 'turno_hora_fin' => '11:00'),
                array('turno_dia' => 'Viernes','turno_hora_inicio' => '12:00', 'turno_hora_fin' => '14:00')
            ));
    }

}