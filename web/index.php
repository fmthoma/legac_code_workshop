<html lang="en">
<head>
    <link rel="stylesheet" href="resources/style.css">
    <link rel="stylesheet" href="resources/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="resources/bootstrap/dist/css/bootstrap-theme.css">
    <script src="resources/jquery-2.1.1.js"></script>
    <script src="resources/bootstrap/dist/js/bootstrap.js"></script>

    <title>Title</title>
</head>
<?php
const HOST = "localhost";
const USER = "board";
const PASS = "board";
const DBNAME = "tngworkshop";
const ENTRIES_PER_PAGE = 10;

require_once("../vendor/autoload.php");

$db = new mysqli(HOST, USER, PASS, DBNAME);
Logger::configure(array(
    'rootLogger' => array(
        'appenders' => array('default'),
    ),
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderConsole',
            'layout' => array(
                'class' => 'LoggerLayoutSimple'
            ),
        )
    )
));
$log = new Logger("myLogger");
$log->setLevel(LoggerLevel::getLevelAll());

if (isset($_POST['user']) && isset($_POST['text'])) {
    $message = add_comment($db);
    $log->debug($message);
}

$page = isset($_GET['p']) ? $_GET['p'] : 1;
$start = ($page - 1) * ENTRIES_PER_PAGE;
$tag = isset($_GET['tag']) ? $_GET['tag'] : null;

$entries = array();
if (!$tag) {
    $result = $db->query('SELECT id, user, date, text from comments ORDER BY date DESC LIMIT ' . $start . ',' . ENTRIES_PER_PAGE);
} else {
    $s_query = <<<S_QUERY
SELECT c.id, c.user, c.date, c.text FROM comments c RIGHT JOIN tags_comments t ON c.id = t.commentId LEFT JOIN tags tt ON tt.id = t.tagId WHERE tt.tag = '$tag';
S_QUERY;
    $result = $db->query($s_query);
}
while (($entry = $result->fetch_assoc()) != null) {
    $entries[] = $entry;
}

$num_rows = $db->query('SELECT COUNT(*) as num_rows FROM comments')->fetch_assoc();
$num_pages = ceil($num_rows['num_rows'] / ENTRIES_PER_PAGE);

$result = $db->query("SELECT tag FROM tags ORDER BY TAG ASC");
$tags = array();
while (($tag = $result->fetch_assoc())) {
    $tags[] = $tag['tag'];
}

function add_comment($db)
{
    $user = $_POST['user'];
    $text = $_POST['text'];

    $message = $db->query('INSERT INTO comments (user, text) VALUES ' . "('$user', '$text')")
        ? "Your entry has been added."
        : $db->error;

    $commentId = $db->insert_id;

    $m = array();
    preg_match_all('/#(\w*)/', $text, $m);
    if (!empty($m)) {
        foreach ($m[1] as $match) {
            $result = $db->query("SELECT id FROM tags WHERE tag = '$match'");
            $data = is_object($result) ? $result->fetch_assoc() : array();
            if (!isset($data['id'])) {
                $db->query('INSERT INTO tags (tag) values (\'' . $match . '\')');
                $tag_id = $db->insert_id;
            } else {
                $tag_id = $data[0][0];
            }
            $db->query("INSERT INTO tags_comments (tagId, commentId) VALUES ($tag_id, $commentId)");
        }

    }

    return $message;
}

?>
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
