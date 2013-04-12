<?
include($_SERVER['DOCUMENT_ROOT'] . "/config.php");
$db    = mysqli_connect($config['host'], $config['user'], $config['password'] , $config['db']);
    
$query = "select * 
    from maps;";

$results = array();
if ($resultSet = mysqli_query($db, $query)):
    while($row = mysqli_fetch_assoc($resultSet)) :
        $results[] = $row; 
    endwhile;
    mysqli_free_result($resultSet);
else:
    trigger_error(mysqli_error($db) . " Query: $query");
endif;


?>
<html>
<head>
    <title>Save and share your Castle Doctrine designs - Castle Fortify</title>
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
<ul>
<? foreach($results as $result) : ?>
<li><a href="/?id=<?= $result['unique_id'] ?>"><?= $result['unique_id'] ?></a></li>
<? endforeach; ?>
</ul>
</div>
</body>
</html>
