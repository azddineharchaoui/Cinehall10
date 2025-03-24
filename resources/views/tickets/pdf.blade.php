<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Movie Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .ticket {
            border: 2px solid #000;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .movie-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>CinéHall</h1>
            <div class="movie-title">{{ $ticket->reservation->session->movie->title }}</div>
        </div>
        
        <div class="info">
            <div class="info-row">
                <div class="info-label">Date:</div>
                <div>{{ $ticket->reservation->session->start_time->format('F j, Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Time:</div>
                <div>{{ $ticket->reservation->session->start_time->format('g:i A') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Theater:</div>
                <div>{{ $ticket->reservation->session->theater->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Seat:</div>
                <div>{{ $ticket->seat->row }}{{ $ticket->seat->number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Type:</div>
                <div>{{ $ticket->reservation->session->type }}</div>
            </div>
        </div>
        
        <div class="qr-code">
            {!! QrCode::size(200)->generate($ticket->qr_code) !!}
            <p>{{ $ticket->qr_code }}</p>
        </div>
    </div>
</body>
</html>