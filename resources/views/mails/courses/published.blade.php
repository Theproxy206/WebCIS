<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curso publicado</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, Helvetica, sans-serif; color: #263238;">

<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background-color: #f4f6f8; padding: 40px 16px;">
    <tr>
        <td align="center">

```
        <table width="100%" cellpadding="0" cellspacing="0" border="0"
               style="max-width: 620px; background-color: #ffffff; border-radius: 10px; overflow: hidden;">

            {{-- Header --}}
            <tr>
                <td style="padding: 28px 36px; background-color: #263238; color: #ffffff;">
                    <div style="font-size: 24px; font-weight: bold;">
                        WebCIS
                    </div>

                    <div style="margin-top: 6px; font-size: 14px; color: #cfd8dc;">
                        Comunidad de Ingeniería en Sistemas
                    </div>
                </td>
            </tr>

            {{-- Content --}}
            <tr>
                <td style="padding: 40px 36px;">

                    <div style="font-size: 14px; font-weight: bold; color: #2e7d32; text-transform: uppercase; letter-spacing: 0.5px;">
                        Curso publicado
                    </div>

                    <h1 style="margin: 12px 0 20px; font-size: 28px; line-height: 1.25; color: #263238;">
                        El curso ya está disponible
                    </h1>

                    <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #546e7a;">
                        El curso
                        <strong style="color: #263238;">
                            {{ $course->cou_title }}
                        </strong>
                        ha sido publicado correctamente y ya se encuentra disponible en WebCIS.
                    </p>

                    {{-- Course --}}
                    <table width="100%" cellpadding="0" cellspacing="0" border="0"
                           style="margin: 24px 0;">
                        <tr>
                            <td style="padding: 18px 20px; background-color: #f1f3f4; border-radius: 8px;">

                                <div style="font-size: 12px; color: #78909c; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Curso
                                </div>

                                <div style="margin-top: 6px; font-size: 22px; font-weight: bold; color: #263238;">
                                    {{ $course->cou_title }}
                                </div>

                                <div style="margin-top: 6px; font-size: 14px; color: #78909c;">
                                    Código: {{ $course->cou_code }}
                                </div>

                            </td>
                        </tr>
                    </table>

                    <p style="margin: 28px 0 0; font-size: 15px; line-height: 1.6; color: #546e7a;">
                        Gracias por contribuir al conocimiento y al crecimiento de la comunidad.
                    </p>

                </td>
            </tr>

            {{-- Footer --}}
            <tr>
                <td style="padding: 24px 36px; background-color: #fafafa; border-top: 1px solid #eceff1;">

                    <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #90a4ae;">
                        Este correo fue enviado automáticamente por WebCIS.
                        Por favor, no respondas directamente a este mensaje.
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
