// We're explicitly pulling in the things
// castle fortify depends on. 

// PubSub is a mediator that we can have modules
// speak to. This will allow us to decouple modules,
// while still allowing them to speak to each other.

require.config({
    paths : {
        "jquery"    : "lib/jquery/jquery.min",
        "pubsub"    : "lib/pubsub/pubsub",
        "bootstrap" : "lib/bootstrap/js/bootstrap.min"
    },
    shim: {
        "bootstrap": {
          deps: ["jquery"],
          exports: "$.fn.popover"
        }
    },
    enforceDefine: true
});

define(['jquery', 'pubsub', 'src/doctrinedata', 'src/mapmodel', 'src/utility', 'bootstrap'], 
    function($, PubSub, DoctrineData, map_model, Utility) {

// Set Variables

var columns = 30;
var rows = 30;
var current_tile_name = "wooden-wall";
var floor_tile_name = "empty-floor";
var build_view = "normal";
var save_disabled = false;
var json_map_data;

var renderPriceView = function() {
    if (total_price > 0)
    {
        $('#total_price_js').html("<strong>Value</strong> $"+total_price);
    }
    else
    {
        $('#total_price_js').html("");
    }
};


// place entrance
PubSub.subscribe('map_model:row_start', function(msg, y) {
    if (y == 14) 
    {
        $('#home').append('<div id="entrance">></div>');
    }
});

var total_price = 0;

// place square tile 
PubSub.subscribe('map_model:column_start', function(msg, data) {
    $('#home').append('<div class="square ' + floor_tile_name + '" id="x' + data.x + 'y' + data.y + '" data-x="' + data.x + '" data-y="' + data.y + '" data-current-tile="empty-floor"></div>');
});

// place clear div for next row
PubSub.subscribe('map_model:row_end', function(msg) {
    $('#home').append('<div class="clear"></div>');
});

// when tile changes
PubSub.subscribe('map_model:set_tile_start', function(msg, data) {
    // subtract the price of the tile being deleted
    total_price -= DoctrineData.getPrice(data.tile_name);
});

// when tile changes
PubSub.subscribe('map_model:set_tile_end', function(msg, data) {
    // update map
    $element = $('#x' + data.x + 'y' + data.y);
    $element.attr('data-current-tile', data.tile_name);
    if (build_view == "wire")
    {
        $element.attr('class', 'square ' + wireView(data.tile_name));
    }
    else
    {
        $element.attr('class', 'square ' + data.tile_name);
    }

    // update price
    total_price += DoctrineData.getPrice(data.tile_name);
    renderPriceView();
});


// refreshing the map
// done when wire view is switched on or off
PubSub.subscribe('map_model:refresh_start', function() {
    total_price = 0;
});

PubSub.subscribe('map_model:refresh_tile', function(msg, data) {

    $element = $('#x' + data.x + 'y' + data.y);
    $element.attr('data-current-tile', data.tile_name);
    if (build_view == "wire")
    {
        $element.attr('class', 'square ' + wireView(data.tile_name));
    }
    else
    {
        $element.attr('class', 'square ' + data.tile_name);
    }

    // update price
    total_price += DoctrineData.getPrice(data.tile_name);
});

PubSub.subscribe('map_model:refresh_end', function() {
    renderPriceView();
});



// bind mouseclicks after dom is populated
PubSub.subscribe('map_model:build_end', function(msg) {
    // Fixes a Chrome drag issue

    $(".square").on({
        selectstart : function(event) {
            event.preventDefault();
            return false;
        },
        mousedown : function() {
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
        },
        mouseup :function() {
            if (!isDragging) { //it's a click
                onClick($(this));
            }
        },
        mouseenter : function() {
            if (isDragging)
            {
                onDrag($(this));
            }
        }
    });

    generateMap();
    generateCode();

});

// build model
map_model.build(columns, rows, floor_tile_name);


// Click on tile = change the current tile

$('.tile').click(function() {
    current_tile_name = $(this).attr('data-current-tile');
});

// Click on square = change the tile to current tile
// If already current tile, change to floor
function onClick($element)
{
    var x = $element.attr('data-x');
    var y = $element.attr('data-y');
    var use_tile_name = floor_tile_name;
    // using the dom to store the data is SCARY!
    if ($element.attr('data-current-tile') != current_tile_name)
    {
        use_tile_name = current_tile_name;
    }

    // change the tile in the model
    // map updates automatically on model change
    map_model.setTile(x,y, use_tile_name);
}

function onDrag($element)
{
    var x = $element.attr('data-x');
    var y = $element.attr('data-y');

    map_model.setTile(x,y, current_tile_name);
}

var isDragging = false;

// Generate Code from Map

function generateCode()
{
    switch($('#code_selection').val())
    {
        case 'doctrine':
            $('#data').val(Utility.mapToDoctrine(map_model.map));
            return;
        case 'json':
            $('#data').val(Utility.mapToJson(map_model.map));
            return;
        case 'custom':
            var template_json = $('#data_template').val().trim();
            try {
                template_data = $.parseJSON(template_json);
            }
            catch(err) {
                console.log("error: " + err);
            }
            $('#data').val(Utility.mapToCustom(map_model.map, template_data));
            return;
        default:
            $('#data').val(Utility.mapToJson(map_model.map));
            return;
    }
}


// refresh code panel with the latest code
$('#code_panel_anchor_js').click(function() {
    generateCode();
});


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

    switch($('#code_selection').val())
    {
        case 'json':
            map_model.loadJson($('#data').val());
            return;
        case 'custom':
            var custom_code = $('#data').val().trim();
            var template_json = $('#data_template').val().trim();
            var json = Utility.customToJson(custom_code, template_json)
            map_model.loadJson(json);
            return;
        case 'doctrine':
            var doctrine = $('#data').val().trim();
            var json = Utility.doctrineToJson(doctrine);
            map_model.loadJson(json);
            return;
        default:
            map_model.loadJson($('#data').val());
            return;
    }

}

$('#load-code').click(function(e) {
    e.preventDefault();
    build_view = "normal";
    $('#wire_view_js').prop('checked', false);
    generateMap();
    generateCode();    
});

$('#save').click(function(e) {
    e.preventDefault();

    generateMap();
    generateCode();    

    $('#save_and_share_js').modal();
    $('#save-result').html("Saving...");

    // this should send code from the model
    $.post('/castles/', 
        {
            "code" : Utility.mapToJson(map_model.map) 
        }, 
        function(data){
            if (data.status == "success") 
            {
                $('#save-result').html("<div class='alert alert-info'>"+data.message + "<br><textarea class='span4'>" + data.link + "</textarea></div>");
            }
            else
            {
                $('#save-result').text("<div class='alert alert-error'>"+data.message+"</div>");
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
        map_model.refreshMap();
    }
    else 
    {
        build_view = "normal";
        map_model.refreshMap();
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

});