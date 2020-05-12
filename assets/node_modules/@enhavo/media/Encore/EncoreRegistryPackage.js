
class EncoreRegistryPackage
{
    constructor(config = null)
    {
        this.config = {};
        if(this.config !== null) {
            this.config = config;
        }
    }

    initEncore(Encore, name)
    {
        if(name === 'enhavo') {
            Encore
                .addEntry('enhavo/image-cropper', './assets/enhavo/image-cropper')
                .addEntry('enhavo/media-library', './assets/enhavo/media-library')
        }
    }

    initWebpackConfig(config, name)
    {

    }
}

module.exports = EncoreRegistryPackage;
