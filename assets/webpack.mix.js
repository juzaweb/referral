const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.disableNotifications();
mix.version();

mix.options({
    postCss: [
        require('postcss-discard-comments') ({removeAll: true})
    ],
    terser: {extractComments: false}
});

mix.setPublicPath(`public/modules/referral`);

mix.styles([
    //
], 'public/modules/referral/css/main.min.css');

mix.combine([
    //
], 'public/modules/referral/js/main.min.js');
