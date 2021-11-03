<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Деньги Маркет Bot</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="/public/css/styles.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
        <link rel="apple-touch-icon" sizes="180x180" href="/public/images/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/public/images/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/public/images/icons/favicon-16x16.png">
        <link rel="manifest" href="/public/images/icons/site.webmanifest">
        <link rel="mask-icon" href="/public/images/icons/safari-pinned-tab.svg" color="#5e852c">
        <meta name="msapplication-TileColor" content="#365908">
        <meta name="theme-color" content="#678f33">
    </head>
    <body>
        <header>
            <div class="wrapper">
                <div class="title">
                    <img src="/public/images/logo.jpg">
                    <h1><a href="/">Панель администратора Деньги Маркет Bot</a></h1>
                </div>
                <div class="user">
                    <?php if ($data['user']){echo '<a href="#">'.$data['user'].'</a><a href="/user/logout">Выход</a>'; }?> 
                </div>
            </div>
        </header>
        <?php echo $data['content']; ?>
        <script type="module" src="/public/js/scripts.js"></script>
        <script src="https://unpkg.com/imask"></script>
    </body>
</html>
