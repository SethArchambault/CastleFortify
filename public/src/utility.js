define(['jquery', 'src/doctrinedata'], function($, DoctrineData) {

    var mapToJson = function(map) {

        var format = '{ "map" : [ \n';
        var tile_name = ""; 
        for (var y = 0; y < map.length; y++) 
        {
            for (var x = 0; x < map[y].length; x++) 
            {
                tile_name = map[y][x].tile_name;
                if (tile_name != "empty-floor") 
                {
                    format +=  '  { "x" : '+x+', "y" : '+y+', "tile" : "'+tile_name+'" },\n';
                }
            }
        }
        format = format.slice(0, -2); 
        format += '\n] }\n';
        return format;
    };

    var mapToCustom = function(map, template_data) {

        var format = "";
        var tile_name = ""; 
        for (var y = 0; y < map.length; y++) 
        {
            for (var x = 0; x < map[y].length; x++) 
            {
                tile_name = map[y][x].tile_name;
                format += template_data.template[tile_name];
            }
            format += "\n";
        }
        return format;
    };

    var mapToDoctrine = function(map) {
        var format = "998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#";
        var tile_name = ""; 
        for (var y = map.length-1; y >= 0 ; y--) 
        {
            for (var x = 0; x < map[y].length; x++) 
            {
                tile_name = map[y][x].tile_name;
                format += DoctrineData.getCode(tile_name) + "#";
                if (DoctrineData.getCode(tile_name) === undefined)
                {
                    console.log(['undefined tile:', tile_name].join(' '));
                }
            }

            // add entrance
            format += (y == 15) ? "998#0#" : "998#998#";
        }
        format += "998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998#998";
        return format;    
    };


    var customToJson = function(custom_map, rules_json) {
        var rules = jsonToObject(rules_json);
        // console.log('custom generate');

        var tile = "";
        var x = 0;
        var y = 0;
        var json = '{ "map" : [ \n';        
        for(var n = 0; n < custom_map.length; n++)
        {
            if (custom_map[n] == "\n") 
            {
                y++;
                x=0;
                continue;
            }
            tile_name = getKey(rules.template, custom_map[n]);
            if (tile_name == "no-change")
            {
                x++;
                continue;
            }
            json +=  '  { "x" : '+x+', "y" : '+y+', "tile" : "'+tile_name+'" },\n';
            x++;
        }
        json = json.slice(0, -2); 
        json += '\n] }\n';

        return json;
    };

    function doctrineToJson(doctrine_raw) 
    {
        //console.log(doctrine_raw);
        if (doctrine_raw == "")
        {
            return;
        }
        data = doctrine_raw.split('#');

        if (data.length > 1024) {
            console.log("Too many items! Should be 1024. Instead got:" + data.length);
        }

        var tile = "";
        var x = 0;
        var y = 29;
        var exterior_wall = 998;
        var json = '{ "map" : [ \n';        
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

            // strip out any : states

            clean_data = data[n].replace(/:.*/g,"");

            tile = DoctrineData.getKey(clean_data);
            
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

            if (tile == "no-change")
            {
                x++;
                continue;
            }
            json +=  '  { "x" : '+x+', "y" : '+y+', "tile" : "'+tile_name+'" },\n';
            x++;
        }
        json = json.slice(0, -2); 
        json += '\n] }\n';

        return json;
    };

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
    };

    function jsonToObject(json)
    {
        try
        {
            object = $.parseJSON(json);
        }
        catch(err)
        {
            console.log(err);
        }

        return object;
    };

    return {
        mapToJson : mapToJson,
        mapToDoctrine : mapToDoctrine,
        mapToCustom  : mapToCustom,
        doctrineToJson : doctrineToJson,
        customToJson : customToJson,
        getKey : getKey
    }
});