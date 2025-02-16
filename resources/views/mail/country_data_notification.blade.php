<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Country Data Notication</title>
</head>
<body>
    <h1>Notification</h1>
    <div class="error-details">
        <p><strong>Country Code: {{$countryCode}}</strong></p>
    </div>

    <?php
       $errors = explode("\n",$errors);

       $errors = array_filter($errors,function($value){
            return !empty($value);
       });

       $errors = array_values($errors);
    ?>
    <p><strong>Message: </strong></p>
    <ul>
        @foreach($errors as $error)
            <ol>{{$error}}</ol>
        @endforeach
    </ul>

    <p>Regards,</p>
    <p>Worldvision & ATI</p>
</body>
</html>