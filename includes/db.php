<?php
try {
    $conn = new PDO("sqlsrv:server=tcp:sqlserver-portafolio.database.windows.net,1433;Database=db_portafolio", "Anselmo", "TU_CONTRASEÑA_AQUÍ");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa con PDO.";
} catch (PDOException $e) {
    echo "Error de conexión con PDO: " . $e->getMessage();
}
?>
