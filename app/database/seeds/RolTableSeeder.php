<?php
/**
 * Created by PhpStorm.
 * User: disolu
 * Date: 13/06/15
 * Time: 04:36 AM
 */

class RolTableSeeder extends Seeder{
    public function run()
    {
        DB::table('Rol')->insert(array(array('rol_nombre' => 'admin','rol_descripcion' => 'Administrador de la aplicación'),
            array('rol_nombre' => 'coordinador','rol_descripcion' => 'Coordinador encargado de administrar horarios e inscripciones'),
            array('rol_nombre' => 'auxiliar','rol_descripcion' => 'Persona encargada de tareas de backoffice, como subir fotos de los usuarios')));
    }

}
