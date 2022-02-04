<?php 

//CRUD
//CREATE = CRIAR = NOVO = ADICIONAR
//READ = LER = CARREGAR = LISTAR
//UPDATE = ATUALIZAR = ALTERAR
//DELETE = APAGAR = EXCLUIR

interface IDao {
  
  public function inserir($obj);
  public function selecionar($id);
  public function listarTodos();
  public function atualizar($obj);
  public function excluir($id);

}
