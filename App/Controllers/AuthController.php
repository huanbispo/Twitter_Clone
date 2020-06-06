<?php

namespace App\Controllers;

//Os recursos do miniFramework
use MF\Controller\Action;
use MF\Model\Container;

//Models

class AuthController extends Action {

    public function autenticar() {
        $usuario = Container::getModel('Usuario');

        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', md5($_POST['senha']));

        $retorno = $usuario->autenticar();

        if ($usuario->__get('id') != '' && $usuario->__get('nome') != '') {
            session_start();

            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');

            header('Location: /timeline');
            
        } else {
            header('location: /?login=erro');
        }
    }

    public function sair() {
        session_start();

        // Destrói a sessão ao fazer logoff
        session_destroy();
        header('Location: /');
    }

}

?>