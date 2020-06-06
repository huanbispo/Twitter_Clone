<?php

namespace MF\Controller;

abstract class Action {

	protected $view;
	protected $layout;

	public function __construct() {
		$this->view = new \stdClass();
    }
    
    
	protected function render($view, $layout = 'layout') {
		$this->view->page = $view;

		// Verifica se o arquivo existe
		if (file_exists("../App/Views/".$layout.".phtml")) {

			require_once "../App/Views/".$layout.".phtml";

		} else {
			$this->content();
		}
	}

	protected function content() {

		// Pega a url do Controller e substitui deixando apenas o nome do controller em lower case (index)
		$classAtual = get_class($this);
		$classAtual = str_replace('App\\Controllers\\', '', $classAtual);
		$classAtual = strtolower(str_replace('Controller', '', $classAtual));

		// faz a requisição de tal view puxando de tal controller
		require_once "../App/Views/".$classAtual."/".$this->view->page.".phtml";
	}

}

?>
