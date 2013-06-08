// Contains tile prices + codes
// along with my unfortunate spelling of "chiwawa"

define([], function() {

    // private properties
    var doctrine_codes = {
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

    var prices = {
        "empty-floor": 0,
        "wooden-wall" : 10,
        "steel-wall" : 20,
        "concrete-wall" : 50,
        "doors" : 20,
        "window" : 15,
        "pit" : 100,
        "power" : 200,
        "wire" : 5,
        "wire-horizontal" : 5,
        "wire-vertical" : 5,
        "wired-wooden-wall" : 20,
        "pressure-toggle-switch-off" : 50,
        "pressure-toggle-switch-on" : 50,
        "sticking-pressure-switch" : 50,
        "rotary-toggle-switch" : 50,
        "wire-bridge" : 10,
        "voltage-triggered-switch" : 20,
        "voltage-triggered-inverted-switch" : 20,
        "powered-door" : 100,
        "electric-floor" : 50,
        "indicator-light" : 10,
        "trap-door" : 200,
        "pitbull" : 200,
        "chiwawa" : 100,
        "cat" : 20,
        "daughter" : 0,
        "vault" : 0,
        "wife" : 0
    };

    // private methods
    var getPrice = function(key) {
        return prices[key];
    };

    var getCode = function(doctrine_key) {
        return doctrine_codes[doctrine_key];
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

    // public functions
    return {
        getCode : getCode,
        getCodeKey : function(code) {
            return getKey(doctrine_codes, code);
        },
        getPrice : getPrice
    };
});