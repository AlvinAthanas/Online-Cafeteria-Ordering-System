<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars($link); ?>" rel="stylesheet">
    <link href="<?php echo htmlspecialchars($main); ?>" rel="stylesheet">
    <link href="w3styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <ul class="nav__list">
            <b id="heading"><?php echo htmlspecialchars($heading); ?></b>
            <li><a  href="<?php echo $log . ".php"; ?>"><i class="fa fa-fw fa-user"></i><?php echo $log; ?></a></li>
            <li><a  href="<?php echo $page . ".php"; ?>"><?php echo $page; ?></a></li>
            <li><a  href="<?php echo $page2 . ".php"; ?>"><?php echo $page2; ?></a></li>

            </ul>
        </nav>
    </header>