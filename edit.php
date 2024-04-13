<?php
// Connessione al database e altre configurazioni
$host = "localhost";
$db = "esercizio 3";
$user = "root";
$pass = "";
$dsn = "mysql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

// Debug: Visualizza i dati inviati dal form
var_dump($_POST);

// Verifica se l'ID del cliente è stato inviato correttamente
if(isset($_POST['id'])) {
    $id = $_POST['id'];
    // Altri controlli e codice per l'aggiornamento nel database
} else {
    echo "ID non specificato";
}

// Recupera l'ID del cliente dalla query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch();

    if (!$client) {
        die("Cliente non trovato.");
    }
} else {
    die("ID non specificato.");
}

// Elabora la modifica quando viene inviato il modulo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'], $_POST['surname'], $_POST['età'], $_POST['id'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $age = $_POST['età'];
    $id = $_POST['id'];

    // Aggiorna nel database
    $stmt = $pdo->prepare("UPDATE users SET name = ?, surname = ?, età = ? WHERE id = ?");
    $stmt->execute([$name, $surname, $age, $id]);

    // Reindirizzamento a index2.php dopo l'aggiornamento
    header('Location: index2.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1 class="mt-5 mb-4">Modifica Cliente</h1>
    <form method="POST" action="edit.php">
    <input type="hidden" name="id" value="<?= $client['id'] ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" name="name" id="name" value="<?= $client['name'] ?>">
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Cognome</label>
            <input type="text" class="form-control" name="surname" id="surname" value="<?= $client['surname'] ?>">
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Età</label>
            <input type="text" class="form-control" name="age" id="age" value="<?= $client['età'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
