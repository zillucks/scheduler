<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notification for Training Attendance</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <style>
        body {
            font-family: 'Nunito', 'Times New Roman', serif;
            width: 100%;
            margin: 0;
            padding: 0;
            font-size: 12pt;
        }
    
        main {
            width: 80%!important;
            margin-top: 40px;
            margin-left: auto;
            margin-right: auto;
        }
    
        table {
            width: 80%!important;
            margin: 20px 0;
            margin-left: auto;
            margin-right: auto;
        }
    
        .name {
            font-size: 13pt;
        }
    
        .bottom-description {
            margin-top: 40px;
        }
    
        .bottom-information,
        .bottom-directorate {
            margin-top: 20px;
        }
    
        .bottom-company {
            margin-top: 80px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <main>
        <p class="name">Yth {{ $participant->identity->full_name }}</p>
        @if ($participant->training_attendance_user_status == 'absent')
            <p>Kami informasikan bahwa pada tanggal {{ $attendance->training_attendance_date }} anda tidak menghadiri training <b>{{ $attendance->training->training_name }}</b>,
                anda dapat mengikuti training di lain kesempatan.</p>
        @elseif($participant->training_attendance_user_status == 'present')
            <p>Atas nama Direktorat IT kami mengucapkan terima kasih atas kehadiran dan partisipasi Anda dalam Training <b>{{ $attendance->training->training_name }}</b> yang telah diselenggarakan pada tanggal {{ $attendance->training_attendance_date }}</p>
            <div class="bottom-description">Kami harapkan Training yang telah dilaksanakan dapat bermanfaat dan berguna bagi pekerjaan Anda.</div>
        @endif
        <div class="bottom-information">Terima Kasih,</div>
        <div class="bottom-directorate">Direktorat Information Technology</div>
        <div class="bottom-company">Company Name</div>
    </main>
</body>
</html>