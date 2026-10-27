<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/build/main.css">
    <script src="/build/main.js"></script>
</head>
<body class="h-dvh flex flex-col">
    <header class="p-3 flex justify-between items-center">
        <div>
            <h3>BarberShop</h3>
        </div>

        <?php if(Auth()::authenticated()): ?>
            <div class="relative" id="menu">
                <button id="toggle" class="flex items-center">
                    <div class="rounded-full bg-green-400 h-12.5 w-12.5 flex justify-center items-center">
                        <p><?php echo Auth()::user()->name[0] ?></p>
                    </div>
                </button>
                <div class="absolute right-0 toolbox">
                    <form action="" method="post">
                        <input type="submit" value="Cerrar sesión" class="button button-error">
                    </form>
                </div>
            </div>
        <?php endif ?>
    </header>
    <div class="content flex-1">
        <?php echo $content ?>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () =>
    {
        const menu = new window.App.Menu('#menu');
    })
</script>
</html>