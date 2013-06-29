
({
    baseUrl: "public",
    name : "app",
    out : "public/app.min.js",
    mainConfigFile : "public/app.js",
    paths : 
    {
        "requireLib" : "lib/require/require"
    },
    insertRequire: ['app'],
    include : [
        "requireLib",
        "app"
    ],
})
