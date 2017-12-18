requirejs(["util"]); // Why can I require this here but jQuery requires dos not work?

// TODO: Figure this out: https://javascriptplayground.com/blog/2012/07/requirejs-amd-tutorial-introduction/
require.config({
    paths: {
        'jQuery': 'lib/jquery.min'
    }
});
