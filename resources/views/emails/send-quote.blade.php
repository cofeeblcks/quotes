<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body style="background-color: #f4f4f4 !important;padding: 50px 0;">
    {{-- Heder --}}
    <x-emails.header />

    <x-emails.content>
        <div style="width: 100%;">
            <div style="width: 100%;margin: 0 5px;">
                <p style="margin: 0;">Estimado cliente <span style="font-weight: bold;">{{ $mailData['customer'] }}</span> adjunto envio cotización según la solicitud. Si tiene alguna duda o inquietud no dude en contactarme.</p>
            </div>

            <div style="width: 100%;margin: 20px 5px 0 5px;">
                <p style="margin-bottom: 10px;">Cordialmente,</p>
                <p style="margin: 0;">{{ $mailData['company']['name'] }}</p>
                <p style="margin: 0;">{{ $mailData['company']['phone'] }}</p>
                <p style="margin: 0;">{{ $mailData['company']['email'] }}</p>
                <img style="margin-top: 10px;" src="{{ url('public/images/logoColor.png') }}" width="100" alt="Logo Mavico" />
            </div>
        </div>
    </x-emails.content>

    {{-- Footer --}}
    <x-emails.footer />
</body>
</html>
