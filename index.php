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
    <title>Save and share your Castle Doctrine designs - Castle Fortify</title>
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
        .daughter2 {background:url(images/daughter.png);}
        .daughter3 {background:url(images/daughter.png);}
        .daughter4 {background:url(images/daughter.png);}
        .son {background:url(images/daughter.png);}
        .son2 {background:url(images/daughter.png);}
        .son3 {background:url(images/daughter.png);}
        .son4 {background:url(images/daughter.png);}
        .vault {background:url(images/vault.png);}
        .wife {background:url(images/wife.png);}

        #entrance {position:absolute; margin-left:-30px; padding:4px 0 0 10px; font-weight:bold; border:1px solid #999; width:18px; height:24px; background:#ffff55;}

        .tile {width:28px; height:28px;float:left;padding-right:3px;}
        #home {width:860px;}
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
        <a class="brand" href="/">Castle Fortify</a>
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

    <h1>Build</h1>
    <div id="tiles" class="well">
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
    <option value="doctrine">Castle Doctrine Format</option>
    <option value="custom">Custom</option>
    <option value="json">JSON</option>
    </select>
    <p class="alert alert-info">Any changes you make to the map will update the code instantly.</p>
    <div class="row">
    <div class="span6">
    <textarea id="data" rows="10" class="span6" style="font-family:monospace;"><?= $code ?>
    </textarea><br>
    </div> <!-- span5 -->
    <div class="span4">
        <textarea id="data_template" rows="10" class="span4" style="display:none;">
{ "template" : {
    "empty-floor" : ".",
    "wooden-wall" : "W",
    "steel-wall" : "=",
    "concrete-wall" : "C",
    "doors" : "d",
    "window" : "n",
    "pit" : "P",
    "power" : "Z",
    "wire" : "~",
    "wired-wooden-wall" : "-",
    "pressure-toggle-switch-off" : "0",
    "pressure-toggle-switch-on" : "1",
    "sticking-pressure-switch" : "S",
    "rotary-toggle-switch" : "|",
    "wire-bridge" : "+",
    "voltage-triggered-switch" : "&",
    "voltage-triggered-inverted-switch" : "%",
    "powered-door" : "D",
    "electric-floor" : "#",
    "trap-door" : "T",
    "pitbull" : "B",
    "chiwawa" : "b",
    "cat" : "c",
    "daughter" : "f",
    "wife" : "F",
    "vault" : "$",
    "no-change" : "x"
}}
        </textarea>
    </div> <!-- span5 -->
    </div> <!-- row -->
    <a href="" class="btn" id="load-code">Apply Code to Map</a>
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
<script src="/fortify.js"></script>

</body>
</html>
