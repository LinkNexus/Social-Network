<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
</body>
</html>
<?php

$link = new \PDO('mysql:dbname=d037ce21;host=w01c290d.kasserver.com;charset=utf8', 'd037ce21', 'ApSH3g5UKvd8KPAH');

$request = $link->prepare('SELECT * FROM cam_rss');
$request->execute();
$sources = $request->fetchAll();

$request = $link->prepare('SELECT titre FROM cam_news');
$request->execute();
$news = $request->fetchAll();

$request = $link->prepare('SELECT * FROM cam_rubriques');
$request->execute();
$rubriques = $request->fetchAll();

foreach ($sources as $source) {
// we first try with "Sud Ouest"

    if ($source['name'] != 'Sud Ouest') {
        continue;
    }

    $items = simplexml_load_file($source['link'])->channel->item;
    foreach ($items as $item) {
        if (!in_array($item->title, $news)) {
            echo 1;
        } else {
            echo 2;
        }
    }
}

?>


