// A JavaScript class.
class MyExampleWebpackPlugin {
    // Define `apply` as its prototype method which is supplied with compiler as its argument
    apply(compiler) {
        // Specify the event hook to attach to
        compiler.hooks.emit.tapAsync(
            'MyExampleWebpackPlugin',
            (compilation, callback) => {

                // Manipulate the build using the plugin API provided by webpack
                //compilation.addModule(/* ... */);

                callback();
            }
        );
    }
}

module.exports = MyExampleWebpackPlugin;