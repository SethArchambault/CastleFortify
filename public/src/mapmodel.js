define(['jquery', 'pubsub'], function($, PubSub) {
    // private properties
    var map = [];
    var default_tile;
    // private methods
    var build = function(columns, rows, _default_tile) {
        default_tile = _default_tile;
        for (var y = 0; y < rows; y++) 
        {
            PubSub.publish('map_model:row_start', y);
            this.map[y] = [];
            for (var x = 0; x < columns; x++) 
            {
                PubSub.publish('map_model:column_start', { x:x, y:y});
                this.map[y][x] = {
                    tile_name : default_tile
                };
            }
            PubSub.publish('map_model:row_end', y);
        }
        PubSub.publish('map_model:build_end');
    };

    var clearMap = function() {
        for (var y = 0; y < map.length; y++) 
        {
            for (var x = 0; x < map[y].length; x++) 
            {
                setTile(x,y,default_tile);
            }
        }
    }

    var refreshMap = function() {
        PubSub.publish('map_model:refresh_start');
        for (var y = 0; y < map.length; y++) 
        {
            for (var x = 0; x < map[y].length; x++) 
            {
                PubSub.publish('map_model:refresh_tile', { x:x, y:y, tile_name:map[y][x].tile_name});
            }
        }
        PubSub.publish('map_model:refresh_end');
    }

    var setTile = function(x, y, tile_name)
    {
        if (!map[y] || !map[y][x]) return;

        PubSub.publish('map_model:set_tile_start', {x:x, y:y, tile_name:map[y][x].tile_name});
        map[y][x].tile_name = tile_name;
        PubSub.publish('map_model:set_tile_end', {x:x, y:y, tile_name:tile_name});
    };

    var loadJson = function (json)
    {
        if (json == "")
        {
            return;
        }
        try {
        var data = $.parseJSON(json);
        } catch (e) {
            return;
        }

        clearMap();
        for(var n = 0; n < data.map.length; n++)
        {
            var x = data.map[n].x;
            var y = data.map[n].y;
            var tile = data.map[n].tile;
            setTile(x,y,tile);
        }
    };


    // public functions / properties
    return {
        map : map,
        build : build,
        setTile : setTile,
        loadJson : loadJson,
        refreshMap : refreshMap
    };
});