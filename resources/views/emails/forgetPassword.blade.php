<!DOCTYPE html>
<html>
<head>
    <title>Go Daily Books</title>
</head>
<body>
    <div style='    background-color: #ee0d0d;background-image: linear-gradient(141deg,#832828 0%,#ee0d0d 51%,#e56868 75%);text-align: center; font-size:30px;color:#111; text-shadow: 2px 2px 5px #111; padding: 10px 0px;'>Go Daily Books</div>
    <div style='background-color: #eee;padding: 25px;color: #555;font-size: 17px;'>
        <p>You can reset password from bellow link:</p>
        <a href="{{ route('reset.password', $token) }}">Reset Password</a>
        <p>Thank you</p>
    </div>
   
    <div style='background-color: #eee;padding:10px 25px;border-top: 1px solid #ccc;text-align:center;'>
        <p style='font-size:15px;color: #999;'>Copyright {{date('Y')}} <a href="{{url('/')}}" target='_blank'></a>Go Daily Books</p>
    </div>
</body>
</html>