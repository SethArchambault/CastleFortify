<?php

if (isset($_POST['code']))
{

    include($_SERVER['DOCUMENT_ROOT'] . "/config.php");
    $db    = mysqli_connect($config['host'], $config['user'], $config['password'] , $config['db']);
    $code = mysqli_escape_string($db, $_POST['code']);

    // check to see if code already exists

    $results = array();

    $query = "select * 
        from maps
        where code = '$code';";
    if ($resultSet = mysqli_query($db, $query)):
        while($row = mysqli_fetch_assoc($resultSet)) :
            $results[] = $row;
        endwhile;
        mysqli_free_result($resultSet);
    else:
        trigger_error(mysqli_error($db) . " Query: $query");
    endif;

    if (count($results) > 0)
    {
        $unique_id = $results[0]['unique_id'];
        die(json_encode(array('status' => 'success', 'message' => 
            'Saved! Share this link:', 'link' => "http://castlefortify.com/?id=$unique_id")));
    }


    // it's unique so create a unique id
    do
    {
        $unique_id = substr(md5(time()), -7);

        // check to make sure this unique id hasn't been used before
        $query = "select * 
            from maps
            where unique_id = '$unique_id';";
        if ($resultSet = mysqli_query($db, $query)):
            $results = array();
            while($row = mysqli_fetch_assoc($resultSet)) :
                $results[] = $row;
            endwhile;
            mysqli_free_result($resultSet);
        else:
            trigger_error(mysqli_error($db) . " Query: $query");
        endif;
    } while (count($results) > 0);

    // alright we've got the unique id, lets add the code and send back the message

    $query = "insert into maps set 
        unique_id = '$unique_id',
        code = '$code';";
    if (!mysqli_real_query($db, $query)):
        die(json_encode(array('status' => 'error', 'message' => 
            'There was a database error.. sorry!')));
    endif;
   
    die(json_encode(array('status' => 'success', 'message' => 
            'Saved! Share this link:', 'link' => "http://castlefortify.com/?id=$unique_id")));
}
die(json_encode(array('status' => 'error', 'message' => 
            'No map found to save!')));