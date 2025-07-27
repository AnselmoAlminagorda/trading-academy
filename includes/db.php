<?php

// **CONFIGURACIÓN DE CONEXIÓN PARA AZURE SQL DATABASE**
// Asegúrate de reemplazar '{your_password_here}' con tu contraseña real.
$serverName = "tcp:sqlserver-portafolio.database.windows.net,1433";
$databaseName = "db_portafolio"; // Asegúrate de que este sea el nombre correcto de tu DB en Azure
$uid = "Anselmo";
$pwd = "@71almercO"; // ¡IMPORTANTE! Reemplaza esto con tu contraseña real

// --- CONEXIÓN USANDO PDO (Recomendado) ---
// PHP Data Objects (PDO) para SQL Server
try {
    $conn = new PDO(
        "sqlsrv:server = $serverName; Database = $databaseName",
        $uid,
        $pwd,
        array(PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8) // Opcional: Para manejar caracteres UTF-8
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexión exitosa a Azure SQL Database (PDO).<br>";

    // **Ejemplo de consulta (opcional):**
    // Puedes descomentar y adaptar esto para probar tu conexión
    // $tsql = "SELECT @@version AS SQLServerVersion;";
    // $stmt = $conn->query($tsql);
    // $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // echo "Versión de SQL Server: " . $row['SQLServerVersion'] . "<br>";

} catch (PDOException $e) {
    echo "Error conectando a SQL Server (PDO): " . $e->getMessage() . "<br>";
    // Para depuración, puedes descomentar la siguiente línea, pero evítalo en producción por seguridad.
    // die(print_r($e));
    exit; // Termina la ejecución si hay un error de conexión
}

// --- CONEXIÓN USANDO LA EXTENSIÓN SQLSRV (Alternativa) ---
// Ten en cuenta que PDO es generalmente preferido por su consistencia entre bases de datos.
// Si ya estás usando PDO para otras bases de datos, mantén el enfoque PDO.

/*
// Descomenta este bloque si prefieres usar la extensión sqlsrv directamente

$connectionInfo = array(
    "UID" => $uid,
    "pwd" => $pwd,
    "Database" => $databaseName,
    "LoginTimeout" => 30,  // Tiempo máximo de espera para la conexión
    "Encrypt" => 1,        // Requerido para Azure SQL Database
    "TrustServerCertificate" => 0 // Requerido para Azure SQL Database
);

$conn_sqlsrv = sqlsrv_connect($serverName, $connectionInfo);

if ($conn_sqlsrv) {
    echo "Conexión exitosa a Azure SQL Database (sqlsrv_connect).<br>";

    // **Ejemplo de consulta (opcional):**
    // $tsql = "SELECT @@version AS SQLServerVersion;";
    // $stmt = sqlsrv_query($conn_sqlsrv, $tsql);
    // if ($stmt === false) {
    //     echo "Error en la consulta: " . print_r(sqlsrv_errors(), true) . "<br>";
    // } else {
    //     $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    //     echo "Versión de SQL Server: " . $row['SQLServerVersion'] . "<br>";
    // }
    // sqlsrv_free_stmt($stmt);
    // sqlsrv_close($conn_sqlsrv); // Cerrar la conexión cuando ya no se necesite

} else {
    echo "Error conectando a SQL Server (sqlsrv_connect):<br>";
    // Muestra los errores de conexión
    die(print_r(sqlsrv_errors(), true));
}
*/

// **MÁS CONSIDERACIONES IMPORTANTES:**

// 1. **Contraseña:** Cambia 'TU_CONTRASEÑA_AQUI' por tu contraseña real.
//    ¡Nunca subas tu contraseña directamente en el código a un repositorio público!
//    Considera usar variables de entorno o un archivo de configuración externo (fuera del directorio web)
//    para almacenar credenciales sensibles en entornos de producción.

// 2. **Extensiones de PHP:** Para que este código funcione, necesitas tener las extensiones de PHP para SQL Server habilitadas:
//    * `php_pdo_sqlsrv.dll` (para la conexión PDO)
//    * `php_sqlsrv.dll` (para la extensión sqlsrv directamente)
//    Estas extensiones no vienen incluidas por defecto con PHP. Necesitas descargarlas
//    desde el sitio de Microsoft para tu versión de PHP y SO, y habilitarlas en tu `php.ini`.
//    Puedes buscar "Microsoft Drivers for PHP for SQL Server" para descargarlos.

// 3. **Firewall de Azure:** Como mencioné anteriormente, asegúrate de que la IP pública
//    del servidor donde se ejecuta tu código PHP (tu máquina local, un servidor web, etc.)
//    esté permitida en la configuración del firewall de tu servidor de Azure SQL Database.
//    Puedes hacerlo desde el portal de Azure, en la sección de "Redes" o "Firewall y redes virtuales"
//    de tu servidor SQL.

// 4. **Nombre de la base de datos:** Asegúrate de que 'db_portafolio' sea el nombre exacto de la base de datos
//    que quieres usar dentro de tu servidor de Azure SQL. Si la base de datos que creaste o restauraste
//    tiene otro nombre, deberás ajustarlo.

// Si necesitas ayuda con la instalación de las extensiones de PHP o el firewall, házmelo saber.
?>