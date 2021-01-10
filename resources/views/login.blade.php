<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BotMan Studio</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        body {
            font-family: "Source Sans Pro", sans-serif;
            margin: 0;
            padding: 0;
            background: radial-gradient(#57bfc7, #45a6b3);
        }

        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .content {
            text-align: center;
        }

        div>form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        div>form>* {
            margin-bottom: 20px;
        }

        div>form>input[type=text], div>form>input[type=password] {
            width: 100%;
            margin-bottom: 20px;
            border-radius: .25rem;
            border: 1px solid #ced4da;
            padding: .375rem .75rem;
            font-family: 'MS Sans Serif';
        }

        div>form>input[type=submit] {
            background-color: #008CBA; /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            transition: 0.4s;
        }
        input[type=submit]:hover {
            background-color: #076D8F;
            transition: 0.4s;
        }
    </style>
</head>
<body>
<div class="container">
    <form action="/botman/login" method="POST"> 
        @method('PUT')
        @csrf
        <h2>Connexion Admin</h2>
        <input placeholder="Login" type="text" id="login" name="login">
        <input placeholder="Mot de passe" type="password" id="pass" name="pass">
        <input type="submit" value="Se connecter">
    </form>
    
</div>

<script src="/js/app.js"></script>
</body>
</html>