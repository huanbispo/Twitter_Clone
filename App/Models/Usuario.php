<?php

namespace App\Models;
use MF\Model\Model;

class Usuario extends Model {

    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    //Salvar
    public function salvar() {
        $query = "INSERT INTO Usuarios(nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash 32 caracteres
        $stmt->execute();

        return $this;
    }

    //Validar se um cadastro pode ser feito
    public function validarCadastro() {
        $valido = true;

        #Code
        if(strlen($this->__get('nome')) < 3) {
            $valido = false;
        }

        if(strlen($this->__get('email')) < 3) {
            $valido = false;
        }

        if(strlen($this->__get('nome')) < 3) {
            $valido = false;
        }        

        return $valido;
    }

    //Recuperar um usuario por email
    public function getUsuarioPorEmail() {
        $query = "SELECT nome, email FROM Usuarios WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        return $stmt->FetchAll(\PDO::FETCH_ASSOC);
    
    }

    public function autenticar() {
        $query = "SELECT id, nome, email FROM Usuarios WHERE email = :email and senha = :senha";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();
        
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario['id'] != '' && $usuario['nome'] != '') {
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);

        }

        return $this;
    }

    public function getAll() {
        $query = "SELECT u.id, u.nome, u.email, ( SELECT COUNT(*) FROM usuarios_seguidores as us WHERE us.id_usuario = :id_usuario AND us.id_usuario_seguindo = u.id) as seguindo_sn FROM Usuarios as u WHERE nome like :nome AND id != :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function seguirUsuario($id_usuario_seguindo) {
        $query = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) VALUES (:id_usuario, :id_usuario_seguindo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();

        return true;
    }

    public function deixarSeguirUsuario($id_usuario_seguindo) {

        $query = "DELETE FROM usuarios_seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);

        $stmt->execute();
        return true;

    }

    // INFORMAÇÃO DO USUARIO
    Public function getInfoUsuario() {
        $query = "SELECT nome FROM Usuarios WHERE id = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // TOTAL DE TWEETS
    Public function getTotalTweets() {
        $query = "SELECT count(*) as total_tweet FROM tweets WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // TOTAL DE USUARIO QUE ESTAMOS SEGUINDO
    Public function getTotalSeguindo() {
        $query = "SELECT count(*) as total_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // TOTAL DE SEGUIDORES
    Public function getAllSeguidores() {
        $query = "SELECT count(*) as total_seguidores FROM usuarios_seguidores WHERE id_usuario_seguindo = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


}

?>