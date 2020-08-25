import Container from "@enhavo/dependency-injection"
(async () => {
    await Container.init();
    (await Container.get('@enhavo/media/ImageCropper/ImageCropperApp')).init();
    (await Container.get('@enhavo/app/Vue/VueApp')).init('app', await Container.get('@enhavo/media/ImageCropper/Components/ImageCropperComponent.vue'));
})();
