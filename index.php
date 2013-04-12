<?php

$code = '{ "map" : [
] }';

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

        body {padding: 0 0 50px 0;}
    </style>
</head>
<body>

<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
        <div class="span11">
        <a class="brand" href="#">Castle Fortify</a>
        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a href="#draw-panel" data-toggle="tab">Build</a></li>
          <li><a href="#code-panel" data-toggle="tab">Code</a></li>
        </ul>
    </div> <!-- span12 -->
    </div>
  </div>
</div>

<div class="container">
<div class="span11">

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

<div class="navbar navbar-fixed-bottom">
      <div class="navbar-inner">
        <div class="container">
            <div class="span11">
        <div style="float:left;width:200px;">
            <a href="#save_and_share_js" id="save" class="btn btn-primary" data-disabled-text="In Wire Mode">Save and Share</a>
        </div>
        <ul class="nav ">
            <li class="navbar-text">
                <label style="float:left;padding:10px 10px 0 0; font-weight:bold;" class="checkbox">
                    <input type="checkbox" id="wire_view_js"></input> Wire View
                </label>
            </li>
            <li class="divider-vertical"></li>
            <li class="navbar-text">
                <span id="total_price_js" class="pull-right"></span>
            </li>
        </ul>
            </div> <!-- span11 -->
        </div> <!-- container -->
    </div>
</div>


  </div> <!-- tab-pane -->

  <div class="tab-pane" id="code-panel">
    <div class="well" id="data-div">
    <h1>Code</h1>
    <select id="code_selection">
    <option value="json">JSON</option>
    <option value="custom">Custom</option>
    </select>
    <p class="alert alert-info">Any changes you make to the map will update the code instantly.</p>
    <div class="row">
    <div class="span5">
    <textarea id="data" rows="5" class="span5"><?= $code ?>
    </textarea><br>
    </div> <!-- span5 -->
    <div class="span5">
        <textarea id="data_template" rows="5" class="span5" style="display:none;">
{ "template" : {
    "empty-floor" : "_",
    "wooden-wall" : "w",
    "steel-wall" : "s",
    "concrete-wall" : "c",
    "doors" : "d",
    "window" : "n",
    "pit" : "p",
    "power" : "v",
    "wire" : ".",
    "wired-wooden-wall" : "W",
    "pressure-toggle-switch-off" : "o",
    "pressure-toggle-switch-on" : "O",
    "sticking-pressure-switch" : "S",
    "rotary-toggle-switch" : "|",
    "wire-bridge" : "B",
    "voltage-triggered-switch" : "1",
    "voltage-triggered-inverted-switch" : "0",
    "powered-door" : "D",
    "electric-floor" : "#",
    "trap-door" : "T",
    "pitbull" : "b",
    "chiwawa" : "h",
    "cat" : "c",
    "daughter" : "C",
    "vault" : "V",
    "wife" : "i"
}}
        </textarea>
    </div> <!-- span5 -->
    </div> <!-- row -->
    <a href="" class="btn" id="load-code">Re-Generate Map from Code</a>
    </div> <!-- well -->
  </div> <!-- tab-pane -->
</div> <!-- tab-content -->
 
</div> <!-- span11 -->
</div> <!-- container -->

<div id="save_and_share_js" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Save and Share</h3>
  </div>
  <div class="modal-body">
    <p> <span id="save-result" class="navbar-text"></span></p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
  </div>
</div>

<script src="/lib/jquery/jquery.min.js"></script>
<script src="/lib/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">

// Set Variables

var columns = 30;
var rows = 30;
var current_tile_name = "wooden-wall";
var floor_tile_name = "empty-floor";
var build_view = "normal";
var save_disabled = false;

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
    if (build_view == "normal")
    {
        generateCode();    
    }
});

// Calculate Price from Tile

function getPrice(tile)
{
    switch(tile)
    {
        case "empty-floor":
        return 0;
        case "wooden-wall":
        return 10;
        case "steel-wall":
        return 20;
        case "concrete-wall":
        return 50;
        case "doors":
        return 20;
        case "window":
        return 15;
        case "pit":
        return 100;
        case "power":
        return 200;
        case "wire":
        return 5;
        case "wired-wooden-wall":
        return 20;
        case "pressure-toggle-switch-off":
        return 50;
        case "pressure-toggle-switch-on":
        return 50;
        case "sticking-pressure-switch":
        return 50;
        case "rotary-toggle-switch":
        return 50;
        case "wire-bridge":
        return 10;
        case "voltage-triggered-switch":
        return 20;
        case "voltage-triggered-inverted-switch":
        return 20;
        case "powered-door":
        return 100;
        case "electric-floor":
        return 50;
        case "trap-door":
        return 200;
        case "pitbull":
        return 200;
        case "chiwawa":
        return 100;
        case "cat":
        return 20;
        case "daughter":
        return 0;
        case "vault":
        return 0;
        case "wife":
        return 0;
        default:
        return 0;

    }
}

// Generate Code from Map

function generateCode()
{
    console.log($('#code_selection').val());
    switch($('#code_selection').val())
    {
        case 'json':
            generateJson();
            return;
        case 'custom':
            generateCustom();
            return;
        default:
            generateJson();
            return;
    }
}

function generateJson()
{
    var json = '{ "map" : [ \n';
    var tile = ""; 
    var total_price = 0;
    for (var y = 0; y < rows; y++) 
    {
        for (var x = 0; x < columns; x++) 
        {
            tile = $('#x'+x+'y'+y).attr('data-current-tile');
            if (tile != floor_tile_name) 
            {
                json += '  { "x" : '+x+', "y" : '+y+', "tile" : "'+tile+'" },\n';
            }
            total_price += getPrice(tile);
        }
    }
    json = json.slice(0, -2);
    json += '\n] }\n';

    if (total_price > 0)
    {
        $('#total_price_js').html("<strong>Value</strong> $"+total_price);
    }
    $('#data').val(json);
}


function generateCustom()
{
    var template_json = $('#data_template').val().trim();
    try
    {
        template_data = $.parseJSON(template_json);
    }
    catch(err)
    {
        console.log(err);
    }
    var custom_language = '';
    var tile = ""; 
    var total_price = 0;
    for (var y = 0; y < rows; y++) 
    {
        for (var x = 0; x < columns; x++) 
        {
            tile = $('#x'+x+'y'+y).attr('data-current-tile');
            custom_language += template_data.template[tile];
            total_price += getPrice(tile);
        }
        custom_language += "\n";
    }

    if (total_price > 0)
    {
        $('#total_price_js').html("<strong>Value</strong> $"+total_price);
    }
    // console.log('custom' + custom_language);

    $('#data').val(custom_language);
}

function wireView(tile)
{
    switch(tile)
    {
        case "wooden-wall":
        return "empty-floor";
        case "steel-wall":
        return "wire";
        case "concrete-wall":
        return "empty-floor";
        case "doors":
        return "empty-floor";
        case "window":
        return "empty-floor";
        case "pit":
        return "empty-floor";
        case "wired-wooden-wall":
        return "wire";
        default:
        return tile;
    }
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

    var total_price = 0;

    for(var n = 0; n < data.map.length; n++)
    {
        var x = data.map[n].x;
        var y = data.map[n].y;
        var tile = data.map[n].tile;
        // console.log('tile:'+tile);
        if (build_view == "wire")
        {
            tile = wireView(tile);
        }
        $('#x'+x+'y'+y).attr('class', 'square '+tile);
        $('#x'+x+'y'+y).attr('data-current-tile', tile);
        total_price += getPrice(tile);
    }
    if (build_view == "normal" && total_price > 0)
    {
        $('#total_price_js').html("<strong>Value</strong> $"+total_price);    
    }
}

$('#load-code').click(function(e) {
    e.preventDefault();
    generateMap();
});
$('#save').click(function(e) {
    e.preventDefault();
    build_view = "normal";
    $('#wire_view_js').prop('checked', false);
    generateMap();
    generateCode();    

    $('#save_and_share_js').modal();
    $('#save-result').html("Saving...");
    $.post('/save_map.php', 
        {
            "code" : $('#data').val().trim() 
        }, 
        function(data){
            if (data.status == "success") 
            {
                $('#save-result').html("<div class='alert alert-info'>"+data.message + "<br><textarea class='span4'>" + data.link + "</textarea></div>");
            }
            else
            {
                $('#save-result').text("<div class='alert alert-error'>"+data.message+"</div>");
                // $('#save-result').attr('class', 'alert alert-error');
            }    
        }, 'json');
});
$('#wire_view_js').click(function(e) {

    if ($(this).prop('checked'))
    {
        build_view = "wire";
        generateMap();
        // $('#save').button('disabled');
        // $('#save').removeClass('btn-primary');
        // save_disabled = true;

    }
    else 
    {
        // $('#save').button('reset')
        build_view = "normal";
        generateMap();
        generateCode();    
        // $('#save').addClass('btn-primary');
        // save_disabled = false;

    }

});

$('#code_selection').change(function() {
    switch ($(this).val())
    {
        case "json":
            $('#data_template').hide();
            generateCode();
        break;
        case "custom":
            $('#data_template').show();
            generateCode();
        break;
        default:
        break;
    }
});

$('#data_template').change(function() {
    generateCode();
});
</script>

</body>
</html>