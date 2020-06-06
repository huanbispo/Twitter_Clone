<?php

namespace MF\Model;
use App\Connection;

class Container {

    public static function getModel($model){

        $class = "\\App\\Models\\".ucfirst($model);

        $conn = Connection::getDb();
        //Retornar o modelo solicitado já instanciado, inclusive com a conexão estabelecida
        return new $class($conn);
    }

}

?>
