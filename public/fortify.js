
// Set Variables

var columns = 30;
var rows = 30;
var current_tile_name = "wooden-wall";
var floor_tile_name = "empty-floor";
var build_view = "normal";
var save_disabled = false;
var json_map_data;


var doctrine_template = {
    "empty-floor" : 0,
    "wooden-wall" : 1  ,
    "steel-wall" : 2  ,
    "concrete-wall" : 3  ,
    "window" : 20 ,
    "doors" : 21 ,
    "powered-door" : 30 ,
    "wired-wooden-wall" : 51 ,
    "pitbull" : 70 ,
    "sticking-pressure-switch" : 100,
    "pressure-toggle-switch-off" : 101,
    "wire" : 102,
    "wire-horizontal" : 121,
    "wire-vertical" : 120,
    "power" : 103,
    "voltage-triggered-switch" : 104,
    "voltage-triggered-inverted-switch" : 105,
    "wire-bridge" : 106,
    "rotary-toggle-switch" : 107,
    "pressure-toggle-switch-on" : 108,
    "electric-floor" : 110,
    "indicator-light" : 109,
    "pit" : 111,
    "trap-door" : 112,
    "cat" : 72,
    "chiwawa" : 71,
    "daughter" : 1040,
    "daughter2" : 1041,
    "daughter3" : 1042,
    "daughter4" : 1043,
    "son" : 1020,
    "son2" : 1021,
    "son3" : 1022,
    "son4" : 1023,
    "vault" : 999,
    "wife" : 1010,
    "wife1" : 1011,
    "wife2" : 1012,
    "wife3" : 1013
};

  

// Create Blocks

for (var y = 0; y < rows; y++) 
{
    if (y == 14) 
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

// Fixes a Chrome drag issue
$(".square").bind("selectstart", function(event) {
    event.preventDefault();
    return false;
});

// Click on square = change the tile to current tile
// If already current tile, change to floor
function onClick($element)
{
    if ($element.attr('data-current-tile') == current_tile_name)
    {
        $element.attr('class', 'square '+floor_tile_name);
        $element.attr('data-current-tile', floor_tile_name);
    }
    else
    {
        $element.attr('class', 'square '+current_tile_name)
        $element.attr('data-current-tile', current_tile_name);
    }
    if (build_view == "normal")
    {
        generateCode();
    }
}

function onDrag($element)
{
    $element.attr('class', 'square '+current_tile_name)
    $element.attr('data-current-tile', current_tile_name);
    if (build_view == "normal")
    {
        generateCode();
    }
}

var isDragging = false;
$(".square")
.mousedown(function() {
    _this = this;
    
    $(window).mousemove(function() {
        $(window).unbind("mousemove");
        isDragging = true;
        
        onDrag($(_this));
    });
    
    $(window).mouseup(function() {
        $(window).unbind("mouseup");
        $(window).unbind("mousemove");
        
        isDragging = false;
    });
})
.mouseup(function() {
    if (!isDragging) { //it's a click
        // $("#throbble").show();
        onClick($(this));
    }
});

$('.square').mouseenter(function() {
    if (isDragging)
    {
        onDrag($(this));
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
        case "wire-horizontal":
        return 5;
        case "wire-vertical":
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
        case "indicator-light":
        return 10;
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
    switch($('#code_selection').val())
    {
        case 'doctrine':
            generateDoctrine();
            return;
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


function generateDoctrine()
{
    var doctrine = "";
    var tile = ""; 
    var total_price = 0;
    // exterior walls
    doctrine = "998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#";
    for (var y = 29; y >= 0; y--) 
    {
        for (var x = 0; x < columns; x++) 
        {
            tile = $('#x'+x+'y'+y).attr('data-current-tile');
            doctrine += doctrine_template[tile] + "#";
            if (doctrine_template[tile] === undefined)
            {
                console.log(tile);
            }
            total_price += getPrice(tile);
        }
        // add in entrance
        if(y==15)
        {
            doctrine += "998#0#";             
        }
        else
        {
            doctrine += "998#998#";            
        }
    }
    doctrine += "998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998";

    if (build_view == "normal")
    {
        if (total_price > 0)
        {
            $('#total_price_js').html("<strong>Value</strong> $"+total_price);
        }
        else
        {
            $('#total_price_js').html("");
        }
    }
    $('#data').val(doctrine);
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
    if (build_view == "normal")
    {
        if (total_price > 0)
        {
            $('#total_price_js').html("<strong>Value</strong> $"+total_price);
        }
        else
        {
            $('#total_price_js').html("");
        }
    }
    json_map_data = json;
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
    if (build_view == "normal")
    {
        if (total_price > 0)
        {
            $('#total_price_js').html("<strong>Value</strong> $"+total_price);
        }
        else
        {
            $('#total_price_js').html("");
        }
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

// this is the wrong way to do this.

function generateMapFromJson()
{
    var json = $('#data').val().trim(); //json_map_data;
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
    if (build_view == "normal")
    {
        if (total_price > 0)
        {
            $('#total_price_js').html("<strong>Value</strong> $"+total_price);
        }
        else
        {
            $('#total_price_js').html("");
        }
    }
}


function generateMapFromDoctrine()
{
    var doctrine_raw = $('#data').val().trim(); //json_map_data;
    //console.log(doctrine_raw);
    if (doctrine_raw == "")
    {
        return;
    }
    data = doctrine_raw.split('#');

    if (data.length > 1024) {
        console.log("Too many items! Should be 1024. Instead got:" + data.length);
    }

    var total_price = 0;

    var tile = "";
    var x = 0;
    var y = 29;
    var exterior_wall = 998;
    for(var n = 0; n < data.length; n++)
    {
        // find : 
        // go down one line every x amount
        if (x > 29) 
        {
            y--;
            x=0;
            continue;
        }

        // bypass entrance tile



        // strip out any : states


        clean_data = data[n].replace(/:.*/g,"");

        tile = getDoctrineKey(doctrine_template, clean_data);
        
        // ignore entrance. It throws off my vibe.



        if (clean_data == exterior_wall) {
            //skip exterior walls
            continue;
        }
        
        if (n == 512)
        {
            console.log('skip entrance ' + tile + ' at x:' + x + 'y: ' + y);
            continue;
        }


        // check if fits
        if (y < 0)
        {
            console.log(tile+' does not fit on the map!')
        }

        if (data[n] != 0) {
            // console.log(data[n]+ " " + tile + " x" + x + " y" + y + "n" + n);
        }
        if (tile == "no-change")
        {
            x++;
            continue;
        }
        // console.log(tile);
        if (build_view == "wire")
        {
            tile = wireView(tile);
        }
        $('#x'+x+'y'+y).attr('class', 'square '+tile);
        $('#x'+x+'y'+y).attr('data-current-tile', tile);
        x++;
        total_price += getPrice(tile);
    }
    if (build_view == "normal" && total_price > 0)
    {
        $('#total_price_js').html("<strong>Value</strong> $"+total_price);    
    }
}


function getDoctrineKey(collection, value)
{   
    //console.log("collect:"+collection + "value:" + value);
    var key = "empty-floor";
    $.each(collection, function(k, v) {
        if (v == value) 
        {
            key = k;
        }
    });
    return key;

}

function getKey(collection, value)
{   
    console.log("collect:"+collection + "value:" + value);
    var key = "empty-floor";
    $.each(collection, function(k, v) {
        if (v.trim() == value) 
        {
            key = k;
        }
    });
    return key;

}

// generate map from custom
// this should not really exist right.. it should just create the json

function generateMapFromCustom()
{
    // console.log('custom generate');
    var custom_map = $('#data').val().trim();
    var template_json = $('#data_template').val().trim();
    try
    {
        template_data = $.parseJSON(template_json);
    }
    catch(err)
    {
        console.log(err);
    }

    var total_price = 0;

    var tile = "";
    var x = 0;
    var y = 0;
    for(var n = 0; n < custom_map.length; n++)
    {
        if (custom_map[n] == "\n") 
        {
            y++;
            x=0;
            continue;
        }
        tile = getKey(template_data.template, custom_map[n]);
        if (tile == "no-change")
        {
            x++;
            continue;
        }
        // console.log(tile);
        if (build_view == "wire")
        {
            tile = wireView(tile);
        }
        $('#x'+x+'y'+y).attr('class', 'square '+tile);
        $('#x'+x+'y'+y).attr('data-current-tile', tile);
        x++;
        total_price += getPrice(tile);
    }
    if (build_view == "normal" && total_price > 0)
    {
        $('#total_price_js').html("<strong>Value</strong> $"+total_price);    
    }
}


// generate map from code

function generateMap()
{
    switch($('#code_selection').val())
    {
        case 'json':
            generateMapFromJson();
            return;
        case 'custom':
            generateMapFromCustom();
            return;
        case 'doctrine':
            generateMapFromDoctrine();
            return;
        default:
            generateMapFromJson();
            return;
    }

}

$('#load-code').click(function(e) {
    e.preventDefault();
    build_view = "normal";
    $('#wire_view_js').prop('checked', false);
    generateMap();
});
$('#save').click(function(e) {
    $('#code_selection').val('json');
    e.preventDefault();
    build_view = "normal";
    $('#wire_view_js').prop('checked', false);
    generateMap();
    generateCode();    

    $('#save_and_share_js').modal();
    $('#save-result').html("Saving...");
    $.post('/castles/', 
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
        }, 'json')
        .fail(function() {
            $('#save-result').html("<div class='alert alert-error'>Scary error! Please tell Seth.</div>");

        });
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
            $('#data_template').hide();
            generateCode();
        break;
    }
});

$('#data_template').change(function() {
    build_view = "normal";
    $('#wire_view_js').prop('checked', false);
    generateCode();
});

generateCode();
