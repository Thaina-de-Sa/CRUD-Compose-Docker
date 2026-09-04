<?php
// editar.php - Formulário e processamento da edição de um produto existente

require 'conexao.php';

$erro = '';
$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

// Se o formulário foi enviado (POST), processa a atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $estoque = $_POST['estoque'] ?? '';

    if ($nome === '' || $estoque === '') {
        $erro = 'Preencha ao menos o nome e o estoque.';
        $produto = ['id' => $id, 'nome' => $nome, 'descricao' => $descricao, 'estoque' => $estoque];
    } else {
        $stmt = $conexao->prepare("UPDATE produto SET nome = ?, descricao = ?, estoque = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nome, $descricao, $estoque, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $erro = 'Erro ao atualizar produto: ' . $conexao->error;
            $produto = ['id' => $id, 'nome' => $nome, 'descricao' => $descricao, 'estoque' => $estoque];
        }

        $stmt->close();
    }
} else {
    // GET: busca os dados atuais do produto para pré-carregar o formulário
    $stmt = $conexao->prepare("SELECT id, nome, descricao, estoque FROM produto WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $produto = $resultado->fetch_assoc();
    $stmt->close();

    if (!$produto) {
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Editar Produto</h1>
            <a href="index.php" class="btn btn-outline-secondary">&laquo; Voltar</a>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="editar.php">

                    <input type="hidden" name="id" value="<?= htmlspecialchars($produto['id']) ?>">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome"
                               value="<?= htmlspecialchars($produto['nome']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($produto['descricao']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="estoque" class="form-label">Estoque</label>
                        <input type="number" class="form-control" id="estoque" name="estoque"
                               value="<?= htmlspecialchars($produto['estoque']) ?>" min="0" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="index.php" class="btn btn-light">Cancelar</a>

                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>