<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19/07/2015
 * Time: 09:11 AM
 */

class LoginController extends BaseController {

    public function  Initialize(){
        return View::make('intranet.auth.login');
    }
    public function ValidateUser(){
        $IsValid = false;

        $userdata = array(
            'username'   => $_GET['username'],
            'password'  => $_GET['password'],
        );

        // attempt to do the login
        if (Auth::attempt($userdata,true)) {
            $IsValid = true;

        } else {
            $IsValid = false;
        }

        return Response::json(array(
            'resultado' =>  $IsValid
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function ValidateRansaUser(){
        $IsValid = false;

        $user = $_GET['usuario'];
        $pass = $_GET['password'];
                
        $user = RansaUsuario::where('ruser_username', $user)->first();

        if ($user) {
            if (Hash::check($pass, $user->ruser_password))
            {
                $IsValid = true;
            }
            else{
                $IsValid = false;
            }
        }
        else{
            $IsValid = false;
        }

        return Response::json(array(
            'resultado' =>  $IsValid
        ), 200
        )->setCallback(Input::get('callback'));
    }

    public function Logout(){
        Auth::logout();

        return Redirect::to('intranet/login');
    }
}