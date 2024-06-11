<?php
// Inclui o arquivo de conexão com o banco de dados
include_once('../Controller/conectaDB.php');

// Inicia a sessão
session_start();

// Verifica se o usuário está logado e se tem permissão para validar transferências
if (!isset($_SESSION['cpf'])) {
    header("Location: login.php");
    exit();
}

// Obtém todas as transferências pendentes
$sql = "SELECT t.id_transferencia, t.id_patrimonio, t.novo_bloco, t.nova_sala, t.cpf_usuario, p.nome_patrimonio, b.nome_bloco_patrimonio, s.nome_local_patrimonio
        FROM transferencias t
        JOIN patrimonio p ON t.id_patrimonio = p.id_patrimonio
        JOIN bloco_patrimonio b ON t.novo_bloco = b.id_bloco_patrimonio
        JOIN local_patrimonio s ON t.nova_sala = s.id_local_patrimonio
        WHERE t.status = 'Pendente'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$transferencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Transferências</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main_container">
        <div class="card">
            <h2>Validar Transferências</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patrimônio</th>
                        <th>Bloco Atual</th>
                        <th>Sala Atual</th>
                        <th>Novo Bloco</th>
                        <th>Nova Sala</th>
                        <th>Usuário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transferencias as $transferencia): ?>
                    <tr>
                        <td><?= htmlspecialchars($transferencia['nome_patrimonio']) ?></td>
                        <td><?= htmlspecialchars($transferencia['nome_bloco_patrimonio']) ?></td>
                        <td><?= htmlspecialchars($transferencia['nome_local_patrimonio']) ?></td>
                        <td><?= htmlspecialchars($transferencia['novo_bloco']) ?></td>
                        <td><?= htmlspecialchars($transferencia['nova_sala']) ?></td>
                        <td><?= htmlspecialchars($transferencia['cpf_usuario']) ?></td>
                        <td>
                            <form action="../Controller/validar_transferencia.php" method="post">
                                <input type="hidden" name="id_transferencia" value="<?= $transferencia['id_transferencia'] ?>">
                                <button type="submit" name="acao" value="aceitar">Aceitar</button>
                                <button type="submit" name="acao" value="negar">Negar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>