<html lang="en">
<head>
    <link rel="stylesheet" href="../resources/style.css">
    <link rel="stylesheet" href="../resources/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../resources/bootstrap/dist/css/bootstrap-theme.css">
    <script src="../resources/jquery-2.1.1.js"></script>
    <script src="../resources/bootstrap/dist/js/bootstrap.js"></script>

    <title>Title</title>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</nav>

<div class="container" role="main">

    <div class="jumbotron">
        <h1>Tags</h1>
        <?php
        foreach ($tags as $tag) {
            echo "<div><a href='?tag=$tag'>$tag</a></div>";
        }
        ?>
    </div>

    <div>
        <!-- Pagination -->
        <div>
            <a href="?p=1">&lt; first</a>
            <?php for ($i = 1; $i <= $num_pages; $i++) {
                if ($i == $page) {
                    echo "<span>$i</span>\n";
                } else {
                    echo("<a href='?p=$i'>$i</a>\n");
                }
            } ?>
            <a href="?p=<?php echo $num_pages ?>">last &gt;</a>
        </div>

        <!-- Entries -->
        <div><?php echo isset($message) ? $message : '' ?></div>
        <?php foreach ($entries as $entry) { ?>
            <div class="message">
                <div class="visible-lg-inline-block">
                    <div class="user visible-lg-inline"><?php echo $entry['user'] ?></div>
                    <div class="date visible-lg-inline"><?php echo $entry['date'] ?></div>
                    <div class="text visible-lg-inline"><?php echo $entry['text'] ?></div>
                </div>
            </div>
        <?php } ?>

        <!-- Pagination -->
        <div>
            <a href="?p=1">&lt; first</a>
            <?php for ($i = 1; $i <= $num_pages; $i++) {
                if ($i == $page) {
                    echo "<span>$i</span>\n";
                } else {
                    echo("<a href='?p=$i'>$i</a>\n");
                }
            } ?>
            <a href="?p=<?php echo $num_pages ?>">last &gt;</a>
        </div>

        <p>Post a comment:</p>

        <form method="post" class="form-inline" role="form">
            <div class="form-group">
                <label for="user">Your Name:</label> <input class="form-control" id="user" name="user" type="text"/>
                <label for="text">Comment:</label> <textarea class="form-control" id="text" name="text"></textarea>
                <input class="form-control" type="submit"/>
            </div>
        </form>

    </div>
</div>
</body>
</html>
