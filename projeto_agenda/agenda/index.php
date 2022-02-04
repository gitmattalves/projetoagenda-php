<?php
session_start();
if (!isset($_SESSION["AGENDA"])) {
  header("Location: login.php");
}

require_once "configuracao.php";

$usuario = unserialize($_SESSION["AGENDA"]);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Document</title>
</head>

<body>

  <div class="container">
    <h1>Agenda de Contatos</h1>
    <h3>Seja bem-vindo,
      <?php echo $usuario->getNome(); ?>.</h3>
    <?php
    $dao = new ContatoDao;
    ?>

    <div class="my-3 p-2 px-3 border rounded">
      <h3>Cadastro de contato</h3>
      <?php

      $nome = "";
      $codigo = "";
      $acao = "inserir";
      $btnCss = "primary";
      $btnLabel = "Adicionar";
      $url = basename($_SERVER["REQUEST_URI"]);
      //echo "<br>". basename($_SERVER["REQUEST_URI"]);
      if (isset($_GET["codigo"])) {
        $codigo = $_GET["codigo"];
        $acao = "editar";
        $contato = $dao->detalhe($codigo, $usuario);
        if ($contato == null) {
          echo "<div class='alert alert-danger' role='alert'>Contato não localizado!</div>";
        } else {
          $nome = $contato->getNome();
          $btnCss = "warning";
          $btnLabel = "Alterar";
        }
        //excluir
        if (isset($_GET["acao"])) {
          if ($_GET["acao"] == "excluir") {
            $dao->excluir($codigo);
            echo "<div class='alert alert-success' role='alert'>Contato excluído com sucesso!</div>";
          }
          $recarregarContato = false;
          //excluir telefone
          if ($_GET["acao"] == "telefone") {
            $id = $_GET["item"];
            $dao->excluirTelefone($id);
            $recarregarContato = true;
          }

          //excluir email
          if ($_GET["acao"] == "email") {
            $id = $_GET["item"];
            $dao->excluirEmail($id);
            $recarregarContato = true;
          }

          if ($recarregarContato) {
            $contato = $dao->detalhe($codigo, $usuario);
          }          
        }
      }

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //atualizar
        //inserir
        if (isset($_POST["nome"])) {
          $nome = $_POST["nome"];
          $acao_formulario = $_POST["acao"];

          $contato = new Contato($usuario);
          $contato->setNome($nome);
          try {
            if ($acao_formulario == 'inserir') {
              $dao->inserir($contato);
              echo "<div class='alert alert-success' role='alert'>Contato criado com sucesso!</div>";
            } else {
              $contato->setId($codigo);
              $dao->atualizar($contato);
              echo "<div class='alert alert-success' role='alert'>Contato alterado com sucesso!</div>";
            }
          } catch (\Throwable $e) {
            echo "<div class='alert alert-danger' role='alert'>Erro ao criar o contato: " . $e->getMessage() . "</div>";
          }
        }
        $recarregarContato = false;
        //adicionar telefone
        if (isset($_POST["numero"])) {
          $numero = $_POST["numero"];
          $contato = new Contato($usuario);
          $contato->setId($codigo);
          $telefone = new Telefone($contato);
          $telefone->setNumero($numero);
          try {
            $dao->adicionarTelefone($telefone);
            echo "<div class='alert alert-success' role='alert'>Telefone adicionado com sucesso!</div>";
            $recarregarContato = true;
          } catch (\Throwable $e) {
            echo "<div class='alert alert-danger' role='alert'>Erro adicionar número de telefone: " . $e->getMessage() . "</div>";
          }
        }

        //adicionar e-mail
        if (isset($_POST["endereco"])) {
          $endereco = $_POST["endereco"];
          $contato = new Contato($usuario);
          $contato->setId($codigo);
          $email = new Email($contato);
          $email->setEndereco($endereco);
          try {
            $dao->adicionarEmail($email);
            echo "<div class='alert alert-success' role='alert'>E-mail adicionado com sucesso!</div>";
            $recarregarContato = true;
          } catch (\Throwable $e) {
            echo "<div class='alert alert-danger' role='alert'>Erro adicionar endereço de e-mail: " . $e->getMessage() . "</div>";
          }
        }
        if ($recarregarContato) {
          $contato = $dao->detalhe($codigo, $usuario);
        }
      }

      $contatos = $dao->listar($usuario);
      ?>

      <form action="<?php echo $url; ?>" method="post">
        <input type="hidden" name="acao" value="<?php echo $acao; ?>">
        <div>
          <label for="nome" class="form-label">Nome:</label>
          <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do do contato" value="<?php echo $nome; ?>" required>
        </div>
        <div class="my-2 text-end">
          <?php
          if ($acao == 'editar') {
          ?>
            <a class='btn btn-primary mx-2' href='index.php' role='button'>Novo contato</a>
          <?php
          }
          ?>
          <button type="submit" class="btn btn-<?php echo $btnCss; ?>"><?php echo $btnLabel; ?></button>
        </div>
        <?php
        if ($acao == 'editar') {
        ?>
          <div>
            <button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#telefoneModal">
              Adicionar Telefone
            </button>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#emailModal">
              Adicionar E-mail
            </button>
          </div>

          <div>
            <h3 class="mt-3">Telefones</h3>
            <ul class="list-group mt-2">
              <?php
              foreach ($contato->getTelefones() as $telefone) {
                $numero = $telefone->getNumero();
                $id = $telefone->getId();
                echo "<li class='list-group-item d-flex justify-content-between'>
                    <span class='flex-fill align-self-center'>$numero</span>
                    <a class='btn btn-danger' href='javascript:void(0);' role='button' onClick=\"confirma_exclusao_item($codigo, $id, 'telefone');\">Excluir</a>            
                  </li>";
              }
              ?>
            </ul>
          </div>

          <div>
            <h3 class="mt-3">E-mail</h3>
            <ul class="list-group mt-2">
              <?php
              foreach ($contato->getEmails() as $email) {
                $endereco = $email->getEndereco();
                $id = $email->getId();
                echo "<li class='list-group-item d-flex justify-content-between'>
                    <span class='flex-fill align-self-center'>$endereco</span>
                    <a class='btn btn-danger' href='javascript:void(0);' role='button' onClick=\"confirma_exclusao_item($codigo, $id, 'email');\">Excluir</a>            
                  </li>";
              }
              ?>
            </ul>
          </div>

        <?php
        }
        ?>
      </form>

    </div>

    <h3 class="mt-3">Contatos</h3>
    <ul class="list-group">
      <?php
      foreach ($contatos as $contato) {
        $nome = $contato->getNome();
        $id = $contato->getId();
        echo "
          <li class='list-group-item d-flex justify-content-between'>
            <span class='flex-fill align-self-center'>$nome</span>

            <div class='d-flex justify-content-between'>
              <a class='btn btn-warning mx-2' href='index.php?codigo=$id' role='button'>Editar</a>

              <a class='btn btn-danger' href='javascript:;' role='button' onClick='confirma_exclusao($id);'>Excluir</a>            
            </div>

          </li>
          ";
      }
      ?>
    </ul>

    <div class="modal fade" id="telefoneModal" tabindex="-1" aria-labelledby="telefoneModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="telefoneModalLabel">Adicionar Telefone</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="<?php echo $url; ?>" method="post" id="formtelefone">
              <label for="nome" class="form-label">Telefone:</label>
              <input type="text" class="form-control" name="numero" id="numero" placeholder="Informe o número para o contato" required>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" form="formtelefone">Adicionar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="emailModalLabel">Adicionar Telefone</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="<?php echo $url; ?>" method="post" id="formemail">
              <label for="endereco" class="form-label">Endereço de e-mail:</label>
              <input type="email" class="form-control" name="endereco" id="endereco" placeholder="email@dominio.com.br" required>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" form="formemail">Adicionar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function confirma_exclusao(codigo) {
      if (confirm('Confirma a exclusão do contato?')) {
        window.location.href = `index.php?codigo=${codigo}&acao=excluir`;
      }
      return false;
    }

    function confirma_exclusao_item(codigo, item, acao) {
      if (confirm('Confirma a exclusão do item?')) {
        window.location.href = `index.php?codigo=${codigo}&acao=${acao}&item=${item}`;
      }
      return false;      
    }

    const fecharFormulario = (formulario, modal) => {
      form = document.getElementById(formulario);
      form.addEventListener('submit', (ev) => {
        const myModalEl = document.getElementById(modal);
        const myModal = bootstrap.Modal.getInstance(myModalEl);
        myModal.hide();
      });
    };

    const formTelefone = fecharFormulario('formtelefone', 'telefoneModal');
    const formEmail = fecharFormulario('formemail', 'emailModal');

    document.addEventListener("DOMContentLoaded", formTelefone);
    document.addEventListener("DOMContentLoaded", formEmail);
  </script>
</body>

</html>