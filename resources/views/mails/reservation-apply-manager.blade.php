<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booking Class Confirmation</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    
    <!-- Styles -->
    <style>
        body { font-family: 'Nunito', 'Times New Roman', serif; width: 100%; margin: 0; padding: 0; font-size: 12pt; } main { width:
        80%!important; margin-top: 40px; margin-left: auto; margin-right: auto; } table { width: 80%!important; margin: 20px 0; margin-left:
        auto; margin-right: auto; } .name { font-size: 13pt; } .bottom-description { margin-top: 40px; } .bottom-information, .bottom-directorate
        { margin-top: 20px; } .bottom-company { margin-top: 80px; font-weight: bold; }
    </style>
</head>
<body>
    <main>
        <p class="name">Yth Atasan {{ $reservation->identity->full_name }}</p>
        <p>Kami informasikan bahwa staff Bapak/Ibu atas nama {{ $identity->full_name }} telah terdaftar pada training {{ $reservation->training->training_name
        }} yang akan diselenggarakan pada :</p>
        <table>
            <tr>
                <td style="width: 25%!important">Hari / Tanggal</td>
                <td>: {{ $reservation->reservation_date }}</td>
            </tr>
            <tr>
                <td>Jam</td>
                <td>: {{ $reservation->time->title }}</td>
            </tr>
            <tr>
                <td>Site</td>
                <td>: {{ $reservation->training->site->site_name }}</td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>: {{ $reservation->training->class->class_name }}</td>
            </tr>
        </table>
        <div class="bottom-information">Demikian Informasinya,</div>
        <div class="bottom-directorate">Direktorat Information Technology</div>
        <div class="bottom-company">Company Name</div>
    </main>
</body>
</html>