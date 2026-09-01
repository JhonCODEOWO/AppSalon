<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirma tu cuenta</title>
</head>
<body>
    <h1>Confirma tu cuenta <?php echo $username ?></h1>
    <p>Para poder utilizar esta cuenta pulsa en el botón.</p>

    <a href="http://localhost:8000/confirm-account/<?php echo $token ?>">Confirmar mi cuenta</a>
</body>
</html>