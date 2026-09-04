<?php
// criar.php - Formulário e processamento do cadastro de um novo produto

require 'conexao.php';

$erro = '';

// Se o formulário foi enviado (POST), processa o cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $estoque = $_POST['estoque'] ?? '';

    if ($nome === '' || $estoque === '') {
        $erro = 'Preencha ao menos o nome e o estoque.';
    } else {
        $stmt = $conexao->prepare("INSERT INTO produto (nome, descricao, estoque) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nome, $descricao, $estoque);

        if ($stmt->execute()) {
            // Cadastro concluído, volta para a listagem
            header("Location: index.php");
            exit;
        } else {
            $erro = 'Erro ao cadastrar produto: ' . $conexao->error;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Novo Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Novo Produto</h1>
            <a href="index.php" class="btn btn-outline-secondary">&laquo; Voltar</a>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="criar.php">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome"
                               value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="estoque" class="form-label">Estoque</label>
                        <input type="number" class="form-control" id="estoque" name="estoque"
                               value="<?= htmlspecialchars($_POST['estoque'] ?? '0') ?>" min="0" required>
                    </div>

                    <button type="submit" class="btn btn-success">Salvar</button>
                    <a href="index.php" class="btn btn-light">Cancelar</a>

                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>