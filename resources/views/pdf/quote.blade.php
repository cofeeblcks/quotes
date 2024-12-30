<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cotización {{ $quote['consecutive'] }}</title>
    <style>
        @page {
            margin: 0;
            font-family: 'Nunito', Arial, Helvetica, sans-serif;
        }

        #wrapper{
            margin: 145px 40px;
            position: relative;
        }

        body{
            background-color: #f4f4f4;
        }

        header {
            position: fixed;
            top: 40px;
            left: 0;
            right: 0;
            padding: 0 40px;
        }

        footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 20px 40px 0 40px;
            border-top: 2px solid #8c52ff;
        }

        .table{
            border: none;
            margin: 3px 0;
            border-collapse: collapse;
        }

        .table thead{
            background-color: #8c52ff;
            color: white;
        }

        .table thead tr th{
            padding: 10px;
        }

        .table tr td {
            border: none;
            padding: 5px;
        }

        .table .tbody tr td{
            padding-top: 10px;
            padding-bottom: 10px;
            background-color: #fff;
            border-bottom: 1px solid #e4e4e4;
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
            background-color: #8c52ff;
        }

        .logo{
            display: flex;
        }
    </style>
</head>

<body>
    <header>
        <table class="w-full">
            <tr>
                <td class="text-left">
                    <img src="{{ public_path('images/logoColor.png') }}" width="150" />
                </td>
                <td class="text-right">
                    <img src="{{ public_path('images/logoText.png') }}" width="250" />
                </td>
            </tr>
        </table>
    </header>
    <div id="wrapper">
        <div style="font-size: 15px;">
            <table class="w-full">
                <tr>
                    <td class="text-right">
                        <span style="font-weight: bold;">Cotización</span> {{ $quote['consecutive'] }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <span style="font-weight: bold;">Fecha</span> {{ $quote['dateQuote'] }}
                    </td>
                </tr>
            </table>

            <h2>Información del cliente</h2>
            <table class="w-full">
                <tr>
                    <td class="text-left">
                        <span style="font-weight: bold;">Nombre</span> {{ $customer['name'] }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">
                        <span style="font-weight: bold;">Teléfono</span> {{ $customer['phone'] }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">
                        <span style="font-weight: bold;">Correo</span> {{ $customer['email'] }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">
                        <span style="font-weight: bold;">Dirección</span> {{ $customer['address'] }}
                    </td>
                </tr>
            </table>

            <p style="margin-top: 15px; margin-bottom: 15px;">
                {{ $quote['description'] }}
            </p>

            <table class="w-full table">
                <thead>
                    <tr>
                        <th style="border-radius: 10px 0 0 0;">Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th style="border-radius: 0 10px 0 0;">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    @foreach ($quote['details'] as $detail)
                        <tr>
                            <td class="text-left" style="padding-left: 20px;">
                                {{ $detail['description'] }}
                            </td>
                            <td class="text-center">
                                {{ $detail['quantity'] }}
                            </td>
                            <td class="text-center">
                                $ {{ number_format($detail['unitCost'], 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                $ {{ number_format(($detail['quantity'] * $detail['unitCost']), 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr class="separator">

            @if( $quote['total'] > 0 )
                <table class="w-full" style="margin-top: 40px;">
                    <tr>
                        <td class="text-right">
                            <span style="font-weight: bold;background-color: #8c52ff;color: white;border-radius: 15px;padding: 20px;font-size: 1rem;">
                                Total $ {{ number_format($quote['total'], 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </table>
            @endif
        </div>

        <table class="w-full" style="margin-top: 80px;">
            <tr>
                <td class="text-center">
                    <img src="{{ public_path($company['signature']) }}" width="150" alt="{{ $company['name'] }}" >
                </td>
            </tr>
        </table>

        <footer>
            <p style="margin-bottom: 10px;font-weight: bold;">Contacto</p>
            <table class="w-full">
                <tr>
                    <td class="text-center" style="border-right: 1px solid #8c52ff;">
                        {{ $company['phone'] }}
                    </td>
                    <td class="text-center" style="border-right: 1px solid #8c52ff;">
                        {{ $company['email'] }}
                    </td>
                    <td class="text-center">
                        {{ $company['address'] }}
                    </td>
                </tr>
            </table>
        </footer>
    </div>
</body>
</html>
