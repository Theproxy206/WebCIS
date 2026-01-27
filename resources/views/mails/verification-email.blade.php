<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de correo</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center" style="padding: 24px;">
            <table width="600" cellpadding="0" cellspacing="0" role="presentation"
                   style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">

                <!-- Header -->
                <tr>
                    <td style="padding: 24px; background-color: #0f172a; color: #ffffff;">
                        <h1 style="margin: 0; font-size: 20px;">
                            WebCIS
                        </h1>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding: 32px; color: #1f2937;">
                        <p style="margin-top: 0;">
                            Hola 👋
                        </p>

                        <p>
                            Recibimos una solicitud para verificar esta dirección de correo electrónico.
                            Si fuiste tú, usa el siguiente código para completar el proceso:
                        </p>

                        <!-- Token box -->
                        <div style="
                                margin: 24px 0;
                                padding: 16px;
                                background-color: #f1f5f9;
                                border-radius: 6px;
                                text-align: center;
                                font-size: 24px;
                                letter-spacing: 4px;
                                font-weight: bold;
                                color: #0f172a;
                            ">
                            {{ $token }}
                        </div>

                        <p>
                            Este código tiene una vigencia limitada.
                            Si no solicitaste esta verificación, puedes ignorar este correo con tranquilidad.
                        </p>

                        <p style="margin-bottom: 0;">
                            Saludos,<br>
                            <strong>Equipo WebCIS</strong>
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding: 16px; background-color: #f8fafc; color: #6b7280; font-size: 12px; text-align: center;">
                        © {{ date('Y') }} WebCIS · Este correo fue generado automáticamente
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
