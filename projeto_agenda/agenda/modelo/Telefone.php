<?php

class Telefone
{

  private $id;
  private $numero;
  private $contato;

  public function __construct($contato)
  {
    $this->contato = $contato;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getNumero()
  {
    return $this->numero;
  }

  public function setNumero($numero)
  {
    $this->numero = $numero;
  }

  public function getContato()
  {
    return $this->contato;
  }

}
