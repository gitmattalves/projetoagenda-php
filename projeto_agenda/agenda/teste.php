<?php 

  require_once "configuracao.php";

  // $conexao = null;

  // try {
  //   $conexao = Conexao::getConnection();
  //   echo "Conexão com banco de dados realizado com sucesso!";
  // } catch (Throwable $th) {
  //   //throw $th;
  //   echo "Erro ao abrir conexão com banco de dados: ". $th->getMessage();
  // }




  //Dao

  echo "<h1>Testes!</h1>";

  // try {
  //   $dao = new UsuarioDao;

  //   $listaUsuario = $dao->listarTodos();

  //   foreach($listaUsuario as $usuario) {
  //     echo $usuario->getNome();
  //   }

  // } catch (\Throwable $e) {
  //   echo "Erro ao carregar lista de usuário: " . $e->getMessage();
  // }

  //perigoso
// try {
//   $dao = new UsuarioDao;
//   //$_POST

//   $usuario = new Usuario;
//   $usuario->setNome($_POST["nome"]);
//   $usuario->setEmail("rodrigocezariosilva@gmail.com");
//   $usuario->setLogin("cezario");
//   $usuario->setSenha("ultrasecreta");

//   //$dao->inserir($usuario);

//   echo "Usuário criado com sucesso!";

//   $listaUsuario = $dao->listarTodos();

//   echo "<h2>Usuários</h2>";

//   foreach($listaUsuario as $usuario) {
//     echo $usuario->getNome() . "<br>";
//   }

// } catch (\Throwable $e) {
//   echo "Erro ao cadastrar usuário: ". $e->getMessage();
// }



try {
  $dao = new UsuarioDao;

  $usuario = $dao->login("ADMIN", "123VAI");
  //var_dump($usuario);
  $daoContato = new ContatoDao;

  $contato = new Contato($usuario);
  $contato->setNome("Novo contato");
  //$daoContato->inserir($contato);

  $contatos = $daoContato->listar($usuario);
  echo "<h2>Contatos</h2>";

  foreach($contatos as $c) {
    //echo $usuario->getNome() . "<br>";
    var_dump($c);
    echo "<br>";
  }

  echo "<h2>Detalhe</h2>";
  $cont = $daoContato->detalhe(1, $usuario);
  var_dump($cont);

} catch (\Throwable $e) {
  echo "Erro ao cadastrar usuário: ". $e->getMessage();
}


