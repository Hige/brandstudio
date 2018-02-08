//const elixir = require('npm');
var elixir = require('laravel-elixir');
var path = require('path');
elixir.config.sourcemaps = true;


require('laravel-elixir-browsersync-official');
require('laravel-elixir-vue-2');



elixir(function(mix) {
    mix.browserSync({
        open: false,
        proxy: {
        target: '127.1:8001' +
        '',
        reqHeaders: function(config) {
            return {
                host: 'localhost:3002'
            };
        }
        },
        port: 3002,
        notify: false
    });

    mix.sass('admin.scss', 'public/css/admin.min.css');
});
