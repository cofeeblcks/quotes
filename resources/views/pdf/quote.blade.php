<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Proyecto {{ $header['code'] }}</title>
    <style>
        @page {
            margin: 145px 40px;
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            position: fixed;
            height: 50px;
            top: -120px;
            left: 0;
            right: 0;
        }

        footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
        }

        .table{
            border: 1px solid rgb(0, 0, 0);
            padding: 8px;
            margin: 3px 0;
            border-collapse: collapse;
        }

        .table tr td {
            border-top: 1px solid rgb(0, 0, 0);
            border-bottom: 1px solid rgb(0, 0, 0);
            border-left: 1px solid rgb(0, 0, 0);
            border-right: 1px solid rgb(0, 0, 0);
            padding: 5px;
        }

        .w-full {
            width: 100%;
        }

        p { margin: 0; }
        .text-right{ text-align: right; }
        .text-center{ text-align: center; }
        .text-left{ text-align: left; }

        .separator {
            height: 1.5px;
            border: none;
            background-color: #000;
        }
    </style>
</head>

<body>
    <header>
        <table class="header w-full" style="color: #393D40;">
            <tr>
                <td class="logo-header">
                    <img src="{{ public_path('unipaz.png') }}" width="100" />
                </td>
                <td style="font-weight: bold;text-align: center;">
                    <p style="text-transform: uppercase; font-size: 14px;">{{ $header['schoolName'] }}</p>
                    <p style="font-size: 14px;">{{ $header['titleModalityStage'] }}</p>
                </td>
            </tr>
        </table>
        <hr class="separator">
    </header>

    <div class="container" style="font-size: 15px;">
        @if (count($students) == 1)
            <p style="margin: 5px 0;">El estudiante</p>
        @else
            <p style="margin: 5px 0;">Los estudiantes</p>
        @endif
        @foreach ($students as $student)
            <table class="w-full table">
                <tr>
                    <td class="text-left">
                        <strong style="color: #393D40;">Nombres: </strong>{{ $student['firstName'] }}
                    </td>
                    <td class="text-left">
                        <strong style="color: #393D40;">Apellidos: </strong>{{ $student['lastName'] }}
                    </td>
                    <td class="text-left">
                        <strong style="color: #393D40;">CC: </strong>{{ $student['identification'] }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">
                        <strong style="color: #393D40;">Teléfono: </strong>{{ $student['phone'] }}
                    </td>
                    <td class="text-left" style="border-left: 1px solid black; border-right: 0">
                        <strong style="color: #393D40;">e-mail: </strong>{{ $student['email'] }}
                    </td>
                    <td class="text-left" style="border-bottom: 1px solid black; border-left: 0;"></td>
                </tr>
                <tr>
                    <td class="text-left">
                        <strong style="color: #393D40;">Programa: </strong>
                    </td>
                    <td class="text-left" style="border-left: 1px solid black; border-right: 0">
                        {{ $student['academicProgram'] }}
                    </td>
                    <td class="text-left" style="border-bottom: 1px solid black; border-left: 0;"></td>
                </tr>
            </table>
        @endforeach

        <p style="margin: 5px 0;">Y el director de trabajo de grado (director propuesto):</p>

        <table class="w-full table">
            <tr>
                <td class="text-left">
                    <strong style="color: #393D40;">Director: </strong>{{ $project['director']['name'] }}
                </td>
            </tr>
        </table>

        <table class="w-full table">
            <tr>
                <td class="text-left">
                    <strong style="color: #393D40;">Titulo preliminar: </strong>{{ $project['title'] }}
                </td>
            </tr>
        </table>

        <table class="w-full table">
            <tr>
                <td class="text-left">
                    <strong style="color: #393D40;">Palabras clave: </strong>{{ $project['keywords'] }}
                </td>
            </tr>
        </table>

        <table class="w-full table">
            <tr>
                <td class="text-left">
                    <strong style="color: #393D40;">Resumen: </strong>{{ $project['resumen'] }}
                </td>
            </tr>
        </table>

        <table class="w-full table">
            <tr>
                <td class="text-left">
                    <strong style="color: #393D40;">Breve descripción del problema: </strong>{{ $project['descriptionProblem'] }}
                </td>
            </tr>
        </table>

        <table class="w-full table">
            <tr>
                <td class="text-left">
                    <strong style="color: #393D40;">Breve descripción de la justificación: </strong>{{ $project['justification'] }}
                </td>
            </tr>
        </table>

        @foreach ($project['aims'] as $index => $aimTypes)
            @foreach ($aimTypes as $aimType => $aims)
                <p style="margin: 5px 0;font-weight: 700;">{{ $aimType }}</p>
                <ul>
                    @foreach ($aims as $aim)
                        <li>{{ $aim }}</li>
                    @endforeach
                </ul>
            @endforeach
        @endforeach

        <table class="w-full table">
            <tr>
                <td class="text-left">
                    <strong style="color: #393D40;">Bibliografía: </strong>{{ $project['bibliography'] }}
                </td>
            </tr>
        </table>

        <table class="w-full">
            <tr>
                @foreach ($students as $student)
                    <td style="margin: 15px 0;">
                        @if ( $student['signature'] )
                            <img src="{{ public_path($student['signature']) }}" width="150" alt="{{ $student['firstName'] }} {{ $student['lastName'] }}" >
                        @else
                            <img src="" width="150" alt="{{ $student['firstName'] }} {{ $student['lastName'] }}" >
                        @endif
                        <p><strong style="color: #393D40;">Firma estudiante: </strong>{{ $student['firstName'] }} {{ $student['lastName'] }}</p>
                        <p><strong style="color: #393D40;">CC: </strong>{{ $student['identification'] }}</p>
                    </td>
                @endforeach
            </tr>
        </table>

        <table class="w-full" style="margin: 15px 0;">
            <tr>
                <td style="">
                    @if ( $project['director']['signature'] )
                        <img src="{{ public_path($project['director']['signature']) }}" width="150" alt="{{ $project['director']['name'] }}" >
                    @else
                        <img src="" width="150" alt="{{ $project['director']['name'] }}" >
                    @endif
                    <p><strong style="color: #393D40;">Firma director: </strong>{{ $project['director']['name'] }}</p>
                    <p><strong style="color: #393D40;">CC: </strong>{{ $project['director']['identification'] }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
