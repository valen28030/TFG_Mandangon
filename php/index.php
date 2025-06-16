<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba API</title>
</head>
<body>

<h2>Guardar Lista de Compra</h2>

<form action="guardar_lista.php" method="POST">
    <label for="nombre">Nombre de la lista:</label><br>
    <input type="text" id="nombre" name="nombre" required><br><br>
    
    <label for="productos">Productos (separados por comas):</label><br>
    <input type="text" id="productos" name="productos" required><br><br>
    
    <input type="submit" value="Guardar Lista">
</form>

</body>
</html>
