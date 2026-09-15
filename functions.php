<?php
// Enfileira o estilo do tema pai
add_action('wp_enqueue_scripts', 'enqueue_parent_styles');
function enqueue_parent_styles()
{
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

// código de inclusáo do acesso ao sakila

function listar_atores_sakila_v2()
{
  // 1. Criamos uma nova conexão com o banco Sakila
  // Substitua 'usuario', 'senha' e 'nome_do_banco' pelos seus dados reais
  $db_sakila = new wpdb('sandropaiva', 'Sandro@123', 'sakila', 'localhost');

  // 2. A Consulta SQL ajustada (5 atores, ordem decrescente pelo primeiro nome)
  $query = "SELECT * FROM constultar_ator";

  $resultados = $db_sakila->get_results($query);

  if (empty($resultados)) {
    // Se ainda falhar, vamos mostrar o erro do banco para diagnosticar
    return "<p>Erro na conexão ou tabela não encontrada: " . $db_sakila->last_error . "</p>";
  }

  $html = '<table style="width:100%; border-collapse: collapse; border: 1px solid #333;">';
  $html .= '<tr style="background-color: #333; color: white;"><th style="padding: 8px;">Nome</th><th style="padding: 8px;">Sobrenome</th></tr>';

  foreach ($resultados as $ator) {
    $html .= '<tr>';
    $html .= '<td style="padding: 8px; border-bottom: 1px solid #ddd;">' . esc_html($ator->first_name) . '</td>';
    $html .= '<td style="padding: 8px; border-bottom: 1px solid #ddd;">' . esc_html($ator->last_name) . '</td>';
    $html .= '</tr>';
  }
  $html .= '</table>';

  return $html;
}
add_shortcode('sakila_lista_v2', 'listar_atores_sakila_v2');


//Formukário para inclusão de ator

function formulario_inserir_ator()
{
  $html = '
    <form method="post" style="padding: 20px; border: 1px solid #ccc; background: #f9f9f9;">
        <h3>Cadastrar Novo Ator</h3>
        <label>Nome:</label><br>
        <input type="text" name="novo_nome" required style="width:100%; margin-bottom:10px;"><br>
        <label>Sobrenome:</label><br>
        <input type="text" name="novo_sobrenome" required style="width:100%; margin-bottom:10px;"><br>
        <input type="submit" name="btn_salvar" value="Salvar Ator" style="background: #2271b1; color: white; border: none; padding: 10px 20px; cursor: pointer;">
    </form>';

  // Lógica para Processar o Formulário quando o botão for clicado
  if (isset($_POST['btn_salvar'])) {
    $db_sakila = new wpdb('sandropaiva', 'Sandro@123', 'sakila', 'localhost');

    $nome = sanitize_text_field($_POST['novo_nome']);
    $sobrenome = sanitize_text_field($_POST['novo_sobrenome']);

    // O comando INSERT do WPDB (Tabela, Dados, Formato)
    $inseriu = $db_sakila->insert(
      'actor',
      array('first_name' => $nome, 'last_name' => $sobrenome),
      array('%s', '%s')
    );

    if ($inseriu) {
      $html .= '<p style="color: green;">Ator ' . $nome . ' cadastrado com sucesso!</p>';
    } else {
      $html .= '<p style="color: red;">Erro ao salvar: ' . $db_sakila->last_error . '</p>';
    }
  }

  return $html;
}
add_shortcode('sakila_cadastro', 'formulario_inserir_ator');


//Fim do formuluário de inclusão de ator

//Atualização do cadastro de ator

function formulario_editar_ator()
{
  $mensagem = '';
  $db_sakila = new wpdb('sandropaiva', 'Sandro@123', 'sakila', 'localhost');

  if (isset($_POST['btn_atualizar'])) {
    $id = intval($_POST['actor_id']); // Garantimos que o ID seja um número inteiro
    $novo_nome = sanitize_text_field($_POST['edit_nome']);

    // O comando UPDATE (Tabela, Novos Dados, Onde(ID), Formato Dados, Formato ID)
    $atualizou = $db_sakila->update(
      'actor',
      array('first_name' => $novo_nome), // O que mudar
      array('actor_id' => $id),         // Qual linha mudar
      array('%s'),                      // Formato do novo dado
      array('%d')                       // Formato do ID
    );

    if ($atualizou !== false) {
      $mensagem = '<p style="color: blue;">✅ Ator ID ' . $id . ' atualizado para ' . $novo_nome . '!</p>';
    } else {
      $mensagem = '<p style="color: red;">❌ Erro ao atualizar.</p>';
    }
  }

  $html = $mensagem;
  $html .= '
    <form method="post" style="padding: 20px; border: 2px solid #ffa500; background: #fff; margin-top: 20px;">
        <h3>Editar Ator (Update)</h3>
        <label>ID do Ator:</label><br>
        <input type="number" name="actor_id" required><br><br>
        <label>Novo Primeiro Nome:</label><br>
        <input type="text" name="edit_nome" required><br><br>
        <button type="submit" name="btn_atualizar" style="background: #ffa500; color: white; border: none; padding: 10px;">Atualizar Nome</button>
    </form>';

  return $html;
}
add_shortcode('sakila_editar', 'formulario_editar_ator');


//Fim da atualização do cadastro de ator


//Inicio da exclusão do Ator

function formulario_deletar_ator()
{
  $mensagem = '';
  $db_sakila = new wpdb('sandropaiva', 'Sandro@123', 'sakila', 'localhost');

  if (isset($_POST['btn_deletar'])) {
    $id = intval($_POST['actor_id_del']);

    // O comando DELETE (Tabela, Onde(ID), Formato ID)
    $deletou = $db_sakila->delete(
      'actor',
      array('actor_id' => $id),
      array('%d')
    );

    if ($deletou) {
      $mensagem = '<p style="color: darkred; font-weight: bold;">🗑️ Ator ID ' . $id . ' removido com sucesso!</p>';
    } else if ($deletou === 0) {
      $mensagem = '<p style="color: orange;">⚠️ Nada foi deletado. O ID ' . $id . ' existe?</p>';
    } else {
      $mensagem = '<p style="color: red;">❌ Erro: Provavelmente este ator está vinculado a um filme.</p>';
    }
  }

  $html = $mensagem;
  $html .= '
    <form method="post" style="padding: 20px; border: 2px solid #ff0000; background: #fff; margin-top: 20px;">
        <h3 style="color: #ff0000;">Excluir Ator (Delete)</h3>
        <label>ID do Ator para remover:</label><br>
        <input type="number" name="actor_id_del" required><br><br>
        <button type="submit" name="btn_deletar" onclick="return confirm(\'Tem certeza disso?\')" style="background: #ff0000; color: white; border: none; padding: 10px; cursor: pointer;">
            EXCLUIR PERMANENTEMENTE
        </button>
    </form>';

  return $html;
}
add_shortcode('sakila_deletar', 'formulario_deletar_ator');


//fim da exclusáo do ator

// fim do cógido do sakila



?>