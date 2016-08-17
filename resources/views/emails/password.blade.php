<!-- resources/views/emails/password.blade.php -->
<a href="{{ url('password/reset/'.$token) }}">Click here to reset your password: {{ url('password/reset/'.$token) }}</a>