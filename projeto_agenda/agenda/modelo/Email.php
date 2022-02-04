<?php

class Email
{

  private $id;
  private $endereco;
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

  public function getEndereco()
  {
    return $this->endereco;
  }

  public function setEndereco($endereco)
  {
    $this->endereco = $endereco;
  }

  public function getContato()
  {
    return $this->contato;
  }

}
