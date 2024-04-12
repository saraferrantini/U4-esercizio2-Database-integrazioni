<?php 
// ------------------------------------comando che connette al database-----------------------------------------------// 
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
 
// ---------------------------------------Verifica se il form è stato inviato e se user_id è stato impostato------------------------------// 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) { 
    $id = $_POST['id']; 
 
    if (!is_numeric($id)) { 
        echo "L'ID utente deve essere un numero."; 
    } else { 
        // Prepara e esegui la query di eliminazione 
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?"); 
        $stmt->execute([$id]); 
        echo "Record eliminato con successo!"; 
    } 
} 


//------------------------------------------Ricerca per nome o cognome---------------------------------------------------//
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $search = $_GET['search'];

    // Prepara la query di ricerca per nome o cognome
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE ? OR surname LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);

    $results = $stmt->fetchAll();
}

?>

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous" 
    /> 
    <title>Document</title> 
    <style> 
        #box { 
            display: flex; 
            justify-content: center; 
            margin-top: 100px; 
          
        } 
 
        body { 
            background-color: aliceblue; 
        } 
 
        form { 
            background-color: aliceblue; 
        } 
    </style> 
    
</head> 
<body> 

<div id="box"> 
    <div id="box"> 
        <form style="width: 500px" method="POST"> 
            <div class="mb-3"> 
                <label for="delete" class="form-label">ID da eliminare</label> 
                <input type="text" class="form-control" name="id" id="id" /> 
            </div> 
 
            <button type="submit" class="btn btn-danger">Elimina</button> 
        </form> 
    </div> 
</div> 
 
<div id="box">
    <form style="width: 500px;" method="GET">
        <div class="mb-3">
            <label for="search" class="form-label">Ricerca per nome</label>
            <input type="text" class="form-control" name="search" id="search" />
        </div>
        <button type="submit" class="btn btn-primary">Cerca</button>
    </form>
</div>

<?php if (isset($results) && count($results) > 0): ?>
    <h3>Risultati della ricerca:</h3>
    <div class="row">
        <?php foreach ($results as $result): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $result['name'] ?></h5>
                        <p class="card-text">ID: <?= $result['id'] ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h3 class="mt-5">Tutti i record nel database:</h3>
<div class="row">
    <?php 
    // Visualizzazione di tutti i record nel database
    $stmt = $pdo->query('SELECT * FROM users'); 
    foreach ($stmt as $row) { ?>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= $row['name'] ?></h5>
                    <h5 class="card-title"><?= $row['surname'] ?></h5>
                    <p class="card-text">ID: <?= $row['id'] ?></p>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous" 
></script> 
</body> 
</html>
