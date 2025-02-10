<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Country Data Error Notication</title>
</head>
<body>
    <h1>Error Notification</h1>
    <div class="error-details">
        <p><strong>Country: {{$context}}</strong></p>
    </div>
    <p><strong>Error Message: </strong></p>
    <ul>
        @foreach($errorMessage as $error)
            <ol>{{$error}}</ol>
        @endforeach
    </ul>

    <p>Regards,</p>
    <p>WorldVision & ATI</p>
</body>
</html>