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
            <div 
            class="relative" id="menu">
                <button id="toggle" class="flex items-center hover:bg-neutral-600 py-1 px-2 rounded-tl rounded-tr overflow-hidden">
                    <div class="rounded-full bg-green-400 h-12.5 w-12.5 flex justify-center items-center">
                        <p><?php echo Auth()::user()->name[0] ?></p>
                    </div>
                </button>
                <div class="absolute right-0 toolbox bg-neutral-600 h-10 px-4 py-1.5">
                    <form action="/logout" method="post">
                        <input type="submit" value="Cerrar sesión" class="">
                    </form>
                </div>
            </div>
        <?php endif ?>
    </header>
    <?php if(Auth()::user()->admin === true): ?>
        <div class="flex gap-x-3 px-1.5 py-4 bg-neutral-900 text-white">
            <a href="/services" class="flex gap-x-2 border border-neutral-500 rounded-full px-3 py-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path fill="currentColor" d="M4 22v-5.866q0-1.835 1.294-3.129t3.129-1.294h1.289V3.634q0-.674.462-1.154q.463-.48 1.134-.48h1.384q.672 0 1.134.48q.463.48.463 1.154v8.078h1.269q1.843 0 3.143 1.293Q20 14.3 20 16.135V22zm1-1h2.75v-3.52q0-.212.144-.355t.357-.144t.356.143t.143.357V21h2.75v-3.52q0-.212.144-.355t.357-.144t.356.143t.143.357V21h2.75v-3.52q0-.212.144-.355t.357-.144t.356.143t.143.357V21H19v-4.866q0-1.442-1.004-2.432t-2.438-.99H8.423q-1.426 0-2.424.998Q5 14.708 5 16.134z" />
                </svg>
                Servicios
            </a>
            <a href="/panel" class="flex gap-x-2 border border-neutral-500 rounded-full px-3 py-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16">
                    <path d="M0 0h16v16H0z" fill="none" />
                    <g fill="currentColor">
                        <path d="M6.445 11.688V6.354h-.633A13 13 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61zm1.188-1.305c.047.64.594 1.406 1.703 1.406c1.258 0 2-1.066 2-2.871c0-1.934-.781-2.668-1.953-2.668c-.926 0-1.797.672-1.797 1.809c0 1.16.824 1.77 1.676 1.77c.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164c-.664 0-1.008-.45-1.05-.82zm2.953-2.317c0 .696-.559 1.18-1.184 1.18c-.601 0-1.144-.383-1.144-1.2c0-.823.582-1.21 1.168-1.21c.633 0 1.16.398 1.16 1.23" />
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                    </g>
                </svg>
                Citas
            </a>
        </div>
    <?php endif ?>
    <div class="content flex-1">
        <?php echo $content ?>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () =>
    {
        const menu = new window.App.Menu('#menu');

        menu.addEventListener('click', (e) => {
            e.detail.elementRef.classList.remove('bg-neutral-600');
            if(!e.detail.state) return;
            e.detail.elementRef.classList.add('bg-neutral-600');
        })
    })
</script>
</html>