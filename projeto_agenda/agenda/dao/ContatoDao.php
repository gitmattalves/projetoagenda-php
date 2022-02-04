<?php

class ContatoDao extends AbstractDao
{

  public function inserir($obj)
  {
    $sql = "insert into Contato (ContatoNome, UserID) values (?, ?)";
    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $obj->getNome(), PDO::PARAM_STR);
    $st->bindValue(2, $obj->getUsuario()->getId(), PDO::PARAM_INT);
    $st->execute();
  }

  public function selecionar($id)
  {
    throw new Exception("Utilize o método detalhe");
  }

  public function detalhe($id, $usuario)
  {
    $contato = null;
    $sql = "select * from Contato where ContatoID = ? and UserID = ?";

    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $id, PDO::PARAM_INT);
    $st->bindValue(2, $usuario->getId(), PDO::PARAM_INT);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $st->execute();
    $reg = $st->fetch();

    if (!empty($reg)) {

      $contato = new Contato($usuario);
      $contato->setId($reg["ContatoID"]);
      $contato->setNome($reg["ContatoNome"]);

      //Telefones
      $sql = "select * from Telefone where ContatoID = ?";
      $stTelefone = $this->conexao->prepare($sql);
      $stTelefone->bindValue(1, $contato->getId(), PDO::PARAM_INT);
      $stTelefone->execute();
      while ($regTelefone = $stTelefone->fetch(PDO::FETCH_ASSOC)) {
        $telefone = new Telefone($contato);
        $telefone->setId($regTelefone["TelID"]);
        $telefone->setNumero($regTelefone["TelNumero"]);
        $contato->adicionarTelefone($telefone);
      }

      //Emails
      $sql = "select * from Email where ContatoID = ?";
      $stEmail = $this->conexao->prepare($sql);
      $stEmail->bindValue(1, $contato->getId(), PDO::PARAM_INT);
      $stEmail->execute();

      while ($regEmail = $stEmail->fetch(PDO::FETCH_ASSOC)) {
        $email = new Email($contato);
        $email->setId($regEmail["EmailID"]);
        $email->setEndereco($regEmail["EmailEnd"]);
        $contato->adicionarEmail($email);
      }
    }
    return $contato;
  }

  public function atualizar($obj)
  {
    $sql = "update Contato set ContatoNome = ? where ContatoID = ?";
    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $obj->getNome(), PDO::PARAM_STR);
    $st->bindValue(2, $obj->getId(), PDO::PARAM_INT);
    $st->execute();
  }

  public function excluir($id)
  {
    $sql = "delete from Contato where ContatoID = ?";
    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $id, PDO::PARAM_INT);
    $st->execute();
  }

  public function listarTodos()
  {
    throw new Exception("Utilize o método listar");
  }

  public function listar($usuario)
  {

    $lista = [];
    $sql = "select * from Contato where UserID = ? order by ContatoNome";

    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $usuario->getId(), PDO::PARAM_INT);
    $st->execute();

    while ($reg = $st->fetch(PDO::FETCH_ASSOC)) {
      $contato = new Contato($usuario);
      $contato->setId($reg["ContatoID"]);
      $contato->setNome($reg["ContatoNome"]);

      //Telefones
      $sql = "select * from Telefone where ContatoID = ?";
      $stTelefone = $this->conexao->prepare($sql);
      $stTelefone->bindValue(1, $contato->getId(), PDO::PARAM_INT);
      $stTelefone->execute();
      while ($regTelefone = $stTelefone->fetch(PDO::FETCH_ASSOC)) {
        $telefone = new Telefone($contato);
        $telefone->setId($regTelefone["TelID"]);
        $telefone->setNumero($regTelefone["TelNumero"]);
        $contato->adicionarTelefone($telefone);
      }

      //Emails
      $sql = "select * from Email where ContatoID = ?";
      $stEmail = $this->conexao->prepare($sql);
      $stEmail->bindValue(1, $contato->getId(), PDO::PARAM_INT);
      $stEmail->execute();

      while ($regEmail = $stEmail->fetch(PDO::FETCH_ASSOC)) {
        $email = new Email($contato);
        $email->setId($regEmail["EmailID"]);
        $email->setEndereco($regEmail["EmailEnd"]);
        $contato->adicionarEmail($email);
      }

      $lista[] = $contato;
    }
    return $lista;
  }

  public function excluirEmail($id)
  {
    $sql = "delete from Email where EmailID = ?";
    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $id, PDO::PARAM_INT);
    $st->execute();
  }

  public function excluirTelefone($id)
  {
    $sql = "delete from Telefone where TelID = ?";
    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $id, PDO::PARAM_INT);
    $st->execute();
  }

  public function atualizarTelefone($obj)
  {
    $sql = "update Telefone set TelNumero = ? where TelID = ?";
    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $obj->getNumero(), PDO::PARAM_STR);
    $st->bindValue(2, $obj->getId(), PDO::PARAM_INT);
    $st->execute();
  }

  public function atualizarEmail($obj)
  {
    $sql = "update Email set EmailEnd = ? where EmailID = ?";
    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $obj->getEndereco(), PDO::PARAM_STR);
    $st->bindValue(2, $obj->getId(), PDO::PARAM_INT);
    $st->execute();
  }

  public function adicionarEmail($obj)
  {
    $sql = "insert into Email (EmailEnd, ContatoID) values (?, ?)";
    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $obj->getEndereco(), PDO::PARAM_STR);
    $st->bindValue(2, $obj->getContato()->getId(), PDO::PARAM_INT);
    $st->execute();
  }

  public function adicionarTelefone($obj)
  {
    $sql = "insert into Telefone (TelNumero, ContatoID) values (?, ?)";
    $st = $this->conexao->prepare($sql);
    $st->bindValue(1, $obj->getNumero(), PDO::PARAM_STR);
    $st->bindValue(2, $obj->getContato()->getId(), PDO::PARAM_INT);
    $st->execute();
  }
}
