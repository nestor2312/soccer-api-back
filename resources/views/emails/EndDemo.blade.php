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
      <img src="https://landing-pi-three-57.vercel.app/Frame_49.png" alt="Logo" style="width:180px; filter:drop-shadow(1px 1px 2px #f4ebeb); margin-bottom:10px;" />
      {{-- <img src="https://tu-dominio.com/img/logo.svg" alt="Logo" style="width:180px; filter:drop-shadow(1px 1px 2px #f4ebeb); margin-bottom:10px;"> --}}
      <h2 style="margin:0; font-weight:600;">Tu periodo de prueba está por finalizar</h2>
    </div>

    <!-- CONTENIDO -->
    <div style="padding:15px; text-align:center; color:#333;">
      <h1 style="
    font-size: 18px; 
    text-transform: uppercase; 
   
    color: #09537e; 
    
    
    margin-bottom: 10px;
">
    Hola{{$usuario->name}}
</h1>
      
      <h2 style="font-size:28px; margin:0;">👋</h2>
<p>Esperamos que hayas disfrutado de tu periodo de prueba en <strong  style="color:#00BF63;">Fubol</strong>. 🎉</p>
              <p>Tu prueba gratuita finalizará el <strong>{{$fechaFin}}</strong>. Para seguir disfrutando de todas las funciones, te invitamos a elegir un plan antes de esa fecha.</p>
     
     <p style="text-align:center; margin:30px 0;">
                <a href="https://fubolzona.com" 
                   style="background-color:#1e90ff; color:#ffffff; padding:12px 25px; text-decoration:none; border-radius:5px; font-weight:bold;">
                   Ver planes
                </a>
              </p>

      <p>Si ya elegiste un plan, ¡gracias por continuar con nosotros! 💪 <br>
              Si tienes dudas o necesitas ayuda, contáctanos a <span style="color:#09537e;">contacto@fubolzona.com</span>.</p>

      

      <p style="margin-top:25px; font-size:14px; color:#494949;">
        Recuerda que una vez finalizado tu periodo de prueba, podrás continuar con el servicio  
        adquiriendo el plan mensual contactándonos directamente.
      </p>
    </div>

    <!-- PIE DE PÁGINA -->
    <div style="background-color:#d7d7d7; text-align:center; padding:15px; font-size:12px; color:#888;">
      © {{ date('Y') }} Fubol — Todos los derechos reservados.<br>
      <a href="https://fubolzona.com" style="color:#00BF63; text-decoration:none;">Visita nuestro sitio</a>
    </div>
  </div>
</body>
</html>
