class ApiPackage
{
    initEncore(Encore)
    {

    }

    initWebpackConfig(config)
    {
        config.resolve.fallback = {
            "stream": require.resolve("stream-browserify"),
            "buffer": require.resolve("buffer"),
            "Buffer": require.resolve("buffer"),
        }

        config.resolve.fallback = {
            stream: require.resolve("stream-browserify")
        }
    }
}

module.exports = ApiPackage;
