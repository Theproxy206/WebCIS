<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curso requiere cambios</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, Helvetica, sans-serif; color: #2d3748;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f6f8; padding: 40px 20px;">
    <tr>
        <td align="center">

```
        <table width="600" cellpadding="0" cellspacing="0" border="0"
               style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 12px; overflow: hidden;">

            <!-- Header -->
            <tr>
                <td style="background-color: #dc2626; padding: 30px; text-align: center;">
                    <h1 style="margin: 0; color: #ffffff; font-size: 28px;">
                        WebCIS
                    </h1>
                    <p style="margin: 8px 0 0; color: #fee2e2; font-size: 15px;">
                        Comunidad de Ingeniería en Sistemas
                    </p>
                </td>
            </tr>

            <!-- Content -->
            <tr>
                <td style="padding: 40px;">

                    <h2 style="margin: 0 0 20px; color: #1f2937; font-size: 24px;">
                        Curso requiere cambios
                    </h2>

                    <p style="margin: 0 0 18px; line-height: 1.6;">
                        El curso
                        <strong>{{ $course->cou_title }}</strong>
                        ha sido revisado, pero por el momento
                        <strong>no ha sido aprobado</strong>.
                    </p>

                    <p style="margin: 0 0 25px; line-height: 1.6;">
                        Puedes realizar los cambios indicados y volver a enviar el curso
                        para una nueva revisión.
                    </p>

                    <!-- Course info -->
                    <table width="100%" cellpadding="0" cellspacing="0" border="0"
                           style="background-color: #f8fafc; border-radius: 8px; margin-bottom: 25px;">
                        <tr>
                            <td style="padding: 18px;">
                                <p style="margin: 0 0 6px; font-size: 13px; color: #64748b;">
                                    CÓDIGO DEL CURSO
                                </p>
                                <p style="margin: 0; font-size: 17px; font-weight: bold; color: #1e293b;">
                                    {{ $course->cou_code }}
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- Commentary -->
                    <h3 style="margin: 0 0 12px; color: #1f2937; font-size: 17px;">
                        Retroalimentación de la revisión
                    </h3>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0"
                           style="margin-bottom: 25px;">
                        <tr>
                            <td style="border-left: 4px solid #dc2626; background-color: #fef2f2; padding: 16px 20px;">
                                <p style="margin: 0; line-height: 1.6; color: #334155;">
                                    {{ $commentary }}
                                </p>
                            </td>
                        </tr>
                    </table>

                    <p style="margin: 0; line-height: 1.6;">
                        Una vez realizados los cambios, podrás volver a enviar el curso
                        para su revisión.
                    </p>

                    <p style="margin: 20px 0 0; line-height: 1.6;">
                        Gracias por contribuir a la comunidad de aprendizaje de WebCIS.
                    </p>

                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="background-color: #f8fafc; padding: 25px 40px; text-align: center;">
                    <p style="margin: 0 0 6px; font-size: 13px; color: #64748b;">
                        Equipo WebCIS
                    </p>
                    <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                        Este correo fue enviado automáticamente. Por favor, no respondas directamente a este mensaje.
                    </p>
                </td>
            </tr>

        </table>

    </td>
</tr>
```

</table>

</body>
</html>
