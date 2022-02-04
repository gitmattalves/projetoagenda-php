<?php 

abstract class AbstractDao implements IDao {

  protected $conexao = null;

  function __construct()
  {
    try {
      $this->conexao = Conexao::getConnection();
    } catch (\Throwable $th) {
      throw $th;
    }
  }

}