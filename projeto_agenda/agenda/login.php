<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>Autenticação de Usuário</h1>
  <form action="login.php" method="post">

    <label for="login">Login:</label>
    <input type="text" name="login" id="login">
    
    <label for="senha">Senha:</label>
    <input type="password" name="senha" id="senha">

    <button type="submit">Enviar</button>

  </form>

  <?php 
  
  session_start();
  require_once "configuracao.php";

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["login"]) && isset($_POST["senha"])){
      $login = $_POST["login"];
      $senha = $_POST["senha"];
      $dao = new UsuarioDao;
      $usuario = $dao->login($login, $senha);
      if($usuario == null) {
        echo "Login ou senha incorreto.";
      }else{
        $_SESSION["AGENDA"] = serialize($usuario);
        header("Location: index.php");
      }
    }
  }
  //fazer logout
  if(isset($_GET["acao"])){

  }

  ?>

</body>
</html>