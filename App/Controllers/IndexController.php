<?php

namespace App\Controllers;

//Os recursos do miniFramework
use MF\Controller\Action;
use MF\Model\Container;

//Models


class IndexController extends Action {

	public function index() {

		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		// Renderiza a View
		$this->render('index');
	}

	public function inscreverse() {

		$this->view->usuario = array(
			'nome' => '',
			'email' => '',
			'senha' => ''
		);

		$this->view->erroCadastro = false;

		// Renderiza a View
		$this->render('inscreverse');
	}

	public function registrar() {
		//Receber os dados do formulario
		$usuario = Container::getModel('Usuario');
		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));
		

		if ($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0) {

			$usuario->salvar();
			$this->render('cadastro');			

		} else {
			#code
			$this->view->usuario = array(
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha']
			);

			$this->view->erroCadastro = true;
			$this->render('inscreverse');
		}	
		 
		//Sucesso


		//Erro


	}

}


?>