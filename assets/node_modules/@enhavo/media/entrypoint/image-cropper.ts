import Container from "@enhavo/dependency-injection"
(async () => {
    await Container.init();
    (await Container.get('@enhavo/media/image-cropper/ImageCropperApp')).init();
    (await Container.get('@enhavo/app/vue/VueApp')).init('app', await Container.get('@enhavo/media/image-cropper/components/ImageCropperComponent.vue'));
})();
