<?php
// index.php - Lista todos os produtos cadastrados

require 'conexao.php';

// Busca todos os produtos no banco
$sql = "SELECT id, nome, descricao, estoque FROM produto ORDER BY id DESC";
$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Produtos</h1>
            <a class="btn btn-success" href="criar.php">+ Novo Produto</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Estoque</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while ($produto = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($produto['id']) ?></td>
                                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill">
                                            <?= htmlspecialchars($produto['estoque']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="editar.php?id=<?= $produto['id'] ?>"
                                           class="btn btn-sm btn-outline-primary">
                                           Editar
                                        </a>
                                        <a href="excluir.php?id=<?= $produto['id'] ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Tem certeza que deseja excluir este produto?');">
                                           Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Nenhum produto cadastrado ainda.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>