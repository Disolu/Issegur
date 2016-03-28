<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/06/2015
 * Time: 01:55 AM
 */

class Factura extends Eloquent
{
    protected $table = 'Factura';
    public $timestamps = false;

    public function user(){
      return $this->hasOne('User','id','userid');
    }

}
