<?php

$code = '';

// check if an id is sent
if (isset($_GET['id']))
{
    $id = $_GET['id'];

    // look up id in database

    include($_SERVER['DOCUMENT_ROOT'] . "/config.php");
    $db    = mysqli_connect($config['host'], $config['user'], $config['password'] , $config['db']);
        
    $id = mysqli_escape_string($db, $id);

    $query = "select * 
        from maps
        where unique_id = '$id';";

    if ($resultSet = mysqli_query($db, $query)):
        if($row = mysqli_fetch_assoc($resultSet)) :
        endif;
        mysqli_free_result($resultSet);
    else:
        trigger_error(mysqli_error($db) . " Query: $query");
    endif;
    $code = stripcslashes(htmlspecialchars($row['code']));

}

?>

<!--
Load shared map
Save shared map

Send ajax of code > database > generate unique id > send back to client



-->

<html>
<head>
    <title>Castle Fortify</title>
    <link href="/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style type="text/css">
        .wooden-wall {background:url(images/panel1.png) -242px -62px;}
        .steel-wall {background:url(images/panel1.png) -337px -62px;}
        .concrete-wall {background:url(images/panel1.png) -433px -62px;}
        .empty-floor {background:#000;}
        /*#floor {background:url(images/panel1.png) -530px -76px;}*/
        .doors {background:url(images/panel1.png) -242px -173px;}
        .window {background:url(images/panel1.png) -337px -181px;}
        .pit {background:url(images/panel1.png) -433px -187px;}
        .power {background:url(images/panel1.png) -530px -187px;}
        .wire {background:url(images/panel1.png) -242px -300px;}
        .wired-wooden-wall {background:url(images/panel1.png) -337px -285px;}
        .pressure-toggle-switch-off {background:url(images/panel1.png) -433px -300px;}
        .pressure-toggle-switch-on {background:url(images/panel1.png) -530px -300px;}
        .sticking-pressure-switch {background:url(images/panel2.png) -242px -75px;}
        .rotary-toggle-switch {background:url(images/panel2.png) -337px -75px;}
        .wire-bridge {background:url(images/panel2.png) -433px -75px;}
        .voltage-triggered-switch {background:url(images/panel2.png) -530px -75px;}
        .voltage-triggered-inverted-switch {background:url(images/panel2.png) -242px -187px;}
        .powered-door {background:url(images/panel2.png) -337px -187px;}
        .electric-floor {background:url(images/panel2.png) -433px -187px;}
        .trap-door {background:url(images/panel2.png) -530px -187px;}
        .pitbull {background:url(images/panel2.png) -242px -292px;}
        .chiwawa {background:url(images/panel2.png) -337px -292px;}
        .cat {background:url(images/panel2.png) -433px -292px;}
        .daughter {background:url(images/daughter.png);}
        .vault {background:url(images/vault.png);}
        .wife {background:url(images/wife.png);}

        #entrance {position:absolute; margin-left:-30px; padding:4px 0 0 10px; font-weight:bold; border:1px solid #999; width:18px; height:24px; background:#ffff55;}

        .tile {width:28px; height:28px;float:left;padding-right:3px;}
        #home {width:960px;}
        .square {width:28px; height:28px; float:left;}
        .square:hover {background:#333;}
        .clear {clear:both;}
    </style>
</head>
<body>
<div class="container">
<div class="span11">

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <a class="brand" href="#">Castle Fortify</a>
    <ul class="nav nav-tabs" id="myTab">
      <li class="active"><a href="#draw-panel" data-toggle="tab">Build</a></li>
      <li><a href="#code-panel" data-toggle="tab">Code</a></li>
    </ul>
  </div>
</div>

<!-- Space for all the tiles -->
<br><br><br>
 
<div class="tab-content">
  <div class="tab-pane active" id="draw-panel">

    <div id="tiles" class="well">
    <h1>Tiles</h1>
        <div class="tile empty-floor" data-current-tile="empty-floor"></div>
        <div class="tile wooden-wall" data-current-tile="wooden-wall"></div>
        <div class="tile steel-wall" data-current-tile="steel-wall"></div>
        <div class="tile concrete-wall" data-current-tile="concrete-wall"></div>
        <div class="tile doors" data-current-tile="doors"></div>
        <div class="tile window" data-current-tile="window"></div>
        <div class="tile pit" data-current-tile="pit"></div>
        <div class="tile power" data-current-tile="power"></div>
        <div class="tile wire" data-current-tile="wire"></div>
        <div class="tile wired-wooden-wall" data-current-tile="wired-wooden-wall"></div>
        <div class="tile pressure-toggle-switch-off" data-current-tile="pressure-toggle-switch-off"></div>
        <div class="tile pressure-toggle-switch-on" data-current-tile="pressure-toggle-switch-on"></div>
        <div class="tile sticking-pressure-switch" data-current-tile="sticking-pressure-switch"></div>
        <div class="tile rotary-toggle-switch" data-current-tile="rotary-toggle-switch"></div>
        <div class="tile wire-bridge" data-current-tile="wire-bridge"></div>
        <div class="tile voltage-triggered-switch" data-current-tile="voltage-triggered-switch"></div>
        <div class="tile voltage-triggered-inverted-switch" data-current-tile="voltage-triggered-inverted-switch"></div>
        <div class="tile powered-door" data-current-tile="powered-door"></div>
        <div class="tile electric-floor" data-current-tile="electric-floor"></div>
        <div class="tile trap-door" data-current-tile="trap-door"></div>
        <div class="tile pitbull" data-current-tile="pitbull"></div>
        <div class="tile chiwawa" data-current-tile="chiwawa"></div>
        <div class="tile cat" data-current-tile="cat"></div>
        <div class="tile daughter" data-current-tile="daughter"></div>
        <div class="tile vault" data-current-tile="vault"></div>
        <div class="tile wife" data-current-tile="wife"></div>


        <div class="clearfix"></div>
    </div> <!-- tiles -->
    <div id="home">
    </div> <!-- home -->
    <br>
<div id="save-result">
</div>
<a href="#" id="save" class="btn btn-primary btn-large">Save and Share</a><br><br>

  </div> <!-- tab-pane -->

  <div class="tab-pane" id="code-panel">
    <div class="well" id="data-div">
    <h1>Code</h1>
    <p class="alert alert-info">Any changes you make to the map will update the code instantly.</p>
    <textarea id="data" rows="5" class="span5"><?= $code ?>
    </textarea><br>
    <a href="" class="btn" id="load-code">Re-Generate Map</a>
    </div> <!-- well -->
  </div> <!-- tab-pane -->
</div> <!-- tab-content -->
 
</div> <!-- span11 -->
</div> <!-- container -->
<script src="/lib/jquery/jquery.min.js"></script>
<script src="/lib/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">

// Set Variables

var columns = 30;
var rows = 30;
var current_tile_name = "wooden-wall";
var floor_tile_name = "empty-floor";
// Create Blocks

for (var y = 0; y < rows; y++) 
{
    if (y == 15) 
    {
        $('#home').append('<div id="entrance">></div>');
    }
    for (var x = 0; x < columns; x++) 
    {
        $('#home').append('<div class="square '+floor_tile_name+'" id="x'+x+'y'+y+'" data-current-tile="empty-floor"></div>');
    }
    $('#home').append('<div class="clear"></div>');
}

generateMap();

// Click on tile = change the current tile

$('.tile').click(function() {
    current_tile_name = $(this).attr('data-current-tile');
});

// Click on square = change the tile to current tile
// If already current tile, change to floor

$('.square').click(function() {
    if ($(this).attr('data-current-tile') == current_tile_name)
    {
        $(this).attr('class', 'square '+floor_tile_name);
        $(this).attr('data-current-tile', floor_tile_name);
    }
    else
    {
        $(this).attr('class', 'square '+current_tile_name)
        $(this).attr('data-current-tile', current_tile_name);
    }
    generateJson();
});

// Generate Code from Map

function generateJson()
{
    var json = '{ "map" : [ \n';
    var tile = ""; 
    for (var y = 0; y < rows; y++) 
    {
        for (var x = 0; x < columns; x++) 
        {
            tile = $('#x'+x+'y'+y).attr('data-current-tile');
            if (tile != floor_tile_name) 
            {
                json += '  { "x" : '+x+', "y" : '+y+', "tile" : "'+tile+'" },\n';
            }
        }
    }
    json = json.slice(0, -2);
    json += '\n] }\n';

    $('#data').text(json);
}

// generate map from code

function generateMap()
{
    var json = $('#data').val().trim();
    if (json == "")
    {
        return;
    }
    try {
    var data = $.parseJSON(json);
    } catch (e) {
        return;
    }

    for(var n = 0; n < data.map.length; n++)
    {
        var x = data.map[n].x;
        var y = data.map[n].y;
        var tile = data.map[n].tile;
        // console.log('tile:'+tile);
        $('#x'+x+'y'+y).attr('class', 'square '+tile);
        $('#x'+x+'y'+y).attr('data-current-tile', tile);
    }
}

$('#load-code').click(function(e) {
    e.preventDefault();
    generateMap();
});

$('#save').click(function(e) {
    e.preventDefault();
    $.post('/save_map.php', {"code" :$('#data').val().trim() }, function(data){
        if (data.status == "success") 
        {
            $('#save-result').html(data.message + "<br><br><textarea class='span5'>" + data.link + "</textarea>");
            $('#save-result').attr('class', 'alert alert-info');           
        }
        else
        {
            $('#save-result').text(data.message);
            $('#save-result').attr('class', 'alert alert-error');
        }
 
    }, 'json');
});

</script>

</body>
</html>