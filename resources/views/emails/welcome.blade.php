<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido a Fubol</title>
  <style>
  @media only screen and (max-width: 600px) {
    body {
      padding: 10px !important;
    }
    h1 {
      font-size: 18px !important;
    }
    p, li {
      font-size: 14px !important;
    }
    a {
      padding: 10px 20px !important;
    }
  }
</style>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif;">
<div style="max-width:600px; width:98%; background-color:#ffffff; border:#d2d2d2 0.04rem solid; margin:30px auto; border-radius:12px; overflow:hidden; box-shadow:0 5px 5px rgba(0,0,0,0.1);">
    
    <!-- ENCABEZADO -->
    <div style="background:linear-gradient(180deg, #00BF63 0%, #09537E 100%); color:#fff; text-align:center; padding:20px;">
      <img src="https://fubolzona.com/Logo.png" alt="Logo" style="width:180px; filter:drop-shadow(1px 1px 2px #f4ebeb); margin-bottom:10px;" />
      {{-- <img src="https://tu-dominio.com/img/logo.svg" alt="Logo" style="width:180px; filter:drop-shadow(1px 1px 2px #f4ebeb); margin-bottom:10px;"> --}}
      <h2 style="margin:0; font-weight:600;">¡Bienvenido a Fubol!</h2>
    </div>

    <!-- CONTENIDO -->
    <div style="padding:15px; text-align:center; color:#333;">
      <h1 style="font-size:18px;  color: #09537e;text-transform: uppercase;  margin-bottom:10px;">
        Hola {{$usuario->name}}
      </h1>
      <h2 style="font-size:28px; margin:0;">👋</h2>

      <p style="font-size:16px; line-height:1.5; margin:20px 0;">
        Gracias por registrarte en <strong style="color:#00BF63;">Fubol</strong>.  
        Estamos emocionados de tenerte con nosotros.
      </p>

      <p style="font-size:16px; line-height:1.5; margin-bottom:20px;">
        Tu cuenta de <strong>prueba</strong> está activa por <strong>14 días</strong>.  
        Durante este tiempo podrás explorar todas las funciones del sistema:
      </p>

      <ul style="text-align:left; display:inline-block; margin:0 auto 25px; padding:0; font-size:15px; line-height:1.6; color:#333;">
        <li>⚽ Crear y gestionar torneos, equipos y jugadores.</li>
        <li>📊 Ver estadísticas y clasificaciones.</li>
        <li>📅 Programar y registrar partidos.</li>
        <li>📷 Subir logos e imágenes personalizadas.</li>
      </ul>

      <a href="{{ url('https://fubolzona.com/login') }}" 
         style="display:inline-block; background-color:#00BF63; color:#fff; text-decoration:none; padding:12px 25px; border-radius:6px; font-weight:bold; box-shadow:0 3px 6px rgba(0,0,0,0.2);">
         Ir al fubol
      </a>

      <p style="margin-top:25px; font-size:14px; color:#494949;">
        Recuerda que una vez finalizado tu periodo de prueba, podrás continuar con el servicio  
        adquiriendo el plan mensual que escojas contactándonos directamente.
      </p>
    </div>

    <!-- PIE DE PÁGINA -->
    <div style="background-color:#d7d7d7; text-align:center; padding:15px; font-size:12px; color:#888;">
      © {{ date('Y') }} Fubol — Todos los derechos reservados.<br>
      <a href="https://fubolzona.com/" style="color:#00BF63; text-decoration:none;">Visita nuestro sitio</a>
    </div>
  </div>
</body>
</html>
{{-- fff --}}