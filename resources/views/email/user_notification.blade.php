<!DOCTYPE html>
<html>
<head>
 <title>New Listener</title>
</head>
<body>
<h1>Thank you for registering ! {{  $name }} {{$email}}</h1>
{{-- style="padding:0px; margin:0px" /> --}} 
<img src="{{ $message->embed(public_path('/images/logo.jpg')) }}" style="padding:0px; margin:0px" />
</body>
</html>