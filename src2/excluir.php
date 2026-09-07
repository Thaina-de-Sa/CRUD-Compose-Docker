<?php
// excluir.php - Processa a exclusão de um produto existente

require 'conexao.php';

$id = $_GET['id'] ?? null;

// Se não veio um ID válido, apenas volta para a listagem
if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

// Usa prepared statement para evitar SQL Injection
$stmt = $conexao->prepare("DELETE FROM produto WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Após excluir (com sucesso ou não), volta para a listagem
header("Location: index.php");
exit;
?>
