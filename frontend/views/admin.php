<?php
session_start();

//Control de acceso de servidor: solo administrador
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador'){
    //Si no está logueado o no pertenece aquí, es expulsado al login inmediato
    header('Location: index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f6f9; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 400px; margin-bottom: 20px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .mensaje { font-size: 14px; margin-top: 10px; text-align: center; font-weight: bold; }
    </style>
    <title>Panel del Administrador - Registro del usuario</title>
</head>
<body>
    <!-- htmlspecialchars previene ataques XSS a renderizar datos al usuario. -->
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></h1>
    <h3>Rol actual: <?php echo $_SESSION['nombre_usuario']; ?> (Acceso Total)</h3>
    <p>Area exclusiva de administración</p>

    <a href="../../backend/api/logout.php">Cerrar Sesión de manera segura...</a>

    <!-- Formulario de registro -->
    <div class="container">
        <h3>Registrar nuevo usuario</h3>
        <form id ="formRegistro" action="">
            <input type="text" id="regUsuario" placeholder="Nombre de usuario" required autocomplete="off">
            <input type="password" id="regPassword" placeholder="Contraseña temporal" required>

            <label for="cmbRol">Asignar Rol:</label>
            <select name="" id="cmbRol" required>
                <option value="">Cargando roles...</option>
            </select>

            <button type="submit">Crear usuario</button>
        </form>
        <div id="msgRegistro" class="mensaje"></div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const cmbRol = document.getElementById('cmbRol');
        const formRegistro = document.getElementById('formRegistro');
        const msgRegistro = document.getElementById('msgRegistro');

        //Cargar los roles en el SELECT DINAMICAMENTE
        try {
            const res = await fetch('../../backend/api/obtener-roles.php'); 
            const roles = await res.json();

            cmbRol.innerHTML = '<option value="">-- Selecciona un Rol--</option>';
            roles.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.id; //Mandamos el ID númerico al backend
                option.textContent = rol.nombre; //Mandamos el nombre al administrador
                cmbRol.appendChild(option);
            });
            //console.log(roles);
        } catch (error) {
            cmbRol.innerHTML = '<option value="">Error al cargar roles</option>';
        }

        //Enviar los datos de registro al servidor
        formRegistro.addEventListener('submit', async(e) => {
            e.preventDefault();

            const usuario = document.getElementById('regUsuario').value.trim();
            const password = document.getElementById('regPassword').value.trim();
            const rol_id = cmbRol.value;

            msgRegistro.style.color = "black";
            msgRegistro.textContent = "Procesando registro...";

            try {
                const respuesta = await fetch('../../backend/api/registar-usuario.php', 
                {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({usuario, password, rol_id})
                });

                const datos = await respuesta.json();

                if(datos.status === 'success'){
                    msgRegistro.style.color = "green";
                    formRegistro.reset(); //Limpia las cajas de texto si fue exitoso
                } else {
                    msgRegistro.style.color = "red";
                }
                msgRegistro.textContent.color = datos.message;
            } catch (error) {
                msgRegistro.style.color = "red";
                msgRegistro.textContent.color = "Error de conexion con el backend.";
            }

        });
    });
</script>
</body>
</html>