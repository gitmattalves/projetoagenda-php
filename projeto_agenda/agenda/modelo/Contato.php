<?php

class Contato
{

  private $id;
  private $nome;
  private $usuario;
  private $emails;
  private $telefones;

  function __construct($usuario) //composição (relação)
  {
    $this->usuario = $usuario;
    $this->emails = [];
    $this->telefones = [];
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getNome()
  {
    return $this->nome;
  }

  public function setNome($nome)
  {
    $this->nome = $nome;
  }

  public function getUsuario()
  {
    return $this->usuario;
  }

  public function getEmails()
  {
    return $this->emails;
  }

  public function setEmails($emails) //vetor de emails
  {
    $this->emails = $emails;
  }

  public function adicionarEmail($email)
  { //adicionar unitário
    $this->emails[] = $email;
  }

  public function getTelefones()
  {
    return $this->telefones;
  }

  public function setTelefones($telefones)
  {
    $this->telefones = $telefones;
  }

  public function adicionarTelefone($telefone)
  {
    $this->telefones[] = $telefone;
  }
  
}
