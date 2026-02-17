<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido a Fubol</title>
  <style>
    @media only screen and (max-width: 600px) {
      body { padding: 10px !important; }
      h1 { font-size: 18px !important; }
      p { font-size: 14px !important; }
      .boton { padding: 12px 20px !important; width: 90% !important; }
    }
  </style>
</head>
<body style="margin:0; padding:0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f4;">
<div style="max-width:600px; width:98%; background-color:#ffffff; border:#d2d2d2 0.04rem solid; margin:30px auto; border-radius:12px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
    
    <div style="background:linear-gradient(180deg, #00BF63 0%, #09537E 100%); color:#fff; text-align:center; padding:30px 20px;">
      <img src="https://fubolzona.com/Logo.png" alt="Fubol Logo" style="width:160px; filter:drop-shadow(1px 1px 2px rgba(0,0,0,0.2)); margin-bottom:15px;" />
      <h2 style="margin:0;  letter-spacing: 1px; font-weight:600;">¡BIENVENIDO A TU ESTADIO DIGITAL! 🏆</h2>
    </div>

    <div style="padding:30px 20px; text-align:center; color:#333;">
      <h1 style="font-size: 20px; color: #09537e; margin-bottom: 15px;">
        HOLA, <span style="text-transform: uppercase;">{{$usuario->name}}</span>
      </h1>
      
      <p style="font-size:16px; line-height:1.6; color: #555;">
        Es un gusto saludarte. Tu registro en <strong>Fubol</strong> se ha completado con éxito. <br>
        Desde este momento, ya tienes acceso total a tu plataforma personalizada para gestionar tu torneo.
      </p>

      <div style="text-align: left; border: 1px solid #09537E; background-color: #fffafa; border-radius: 8px; padding: 20px; margin: 25px 0;">
        <p style="margin-bottom: 10px; font-weight: bold; color: #09537e;">Tus primeros pasos:</p>
        <ul style="padding-left: 20px; font-size: 15px; color: #444; line-height: 1.8;">
          <li>Registra el nombre de tu torneo.</li>
          <li>Registra los equipos participantes.</li>
          <li>Crea el calendario de partidos.</li>
          <li>¡Comparte el link público con tus jugadores!</li>
        </ul>
      </div>

      <a href="{{ url('https://campeonatos.fubolzona.com/') }}" class="boton" 
         style="display:inline-block; background-color:#00BF63; color:#ffffff; padding:15px 35px; text-decoration:none; border-radius:8px; font-weight:bold; font-size: 16px; box-shadow:0 4px 10px rgba(0,191,99,0.3);">
         COMENZAR A ORGANIZAR
      </a>

      <p style="margin-top:40px; font-size:13px; color:#888; border-top: 1px solid #eee; padding-top: 20px;">
        <strong>Soporte Técnico:</strong> Estamos para apoyarte. Si tienes dudas durante la carga de datos, escríbenos a <span style="color:#09537e; font-weight: bold;">contacto@fubolzona.com</span>
      </p>
    </div>

    <div style="background-color:#f4f4f4; text-align:center; padding:20px; font-size:12px; color:#999;">
      © 2026 Fubol — Todos los derechos reservados.<br>
      <a href="https://fubolzona.com" style="color:#00BF63; text-decoration:none; font-weight: bold;">Visita nuestro sitio web</a>
    </div>
  </div>
</body>
</html>