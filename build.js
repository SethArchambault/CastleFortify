({
    appDir: "public",
    baseUrl: ".",
    dir: "public/build",
    paths : 
    {
        "jquery"    : "lib/jquery/jquery.min",
        "pubsub"    : "lib/pubsub/pubsub",
        "bootstrap" : "lib/bootstrap/js/bootstrap.min"
    },
    modules: [
        {
            name: "app"
        }
    ]
})
