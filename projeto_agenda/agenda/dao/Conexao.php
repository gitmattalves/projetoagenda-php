<?php

class Conexao
{ //Singleton (padrão gof)

  private static $conexao = null; //recurso
  //não permitir a criação ou copia do objeto
  private function __construct()
  {
  }

  //unico ponto de acesso
  public static function getConnection()
  { //getInstance
    //código de criação da conexão...
    if (!isset(self::$conexao)) {
      //crio a conexão..
      self::$conexao = new PDO('mysql:host=localhost;dbname=Agenda', 'root', '');
    }
    return self::$conexao;
  }

  function __clone()
  {
    //lançar uam exceção...
    throw new Exception('Não se pode clonar um singleton');
  }
}
