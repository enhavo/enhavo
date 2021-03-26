import Container from "@enhavo/dependency-injection"
(async () => {
    await Container.init();
    (await Container.get('@enhavo/media/media-library/MediaLibraryApp')).init();
    (await Container.get('@enhavo/app/vue/VueApp')).init('app', await Container.get('@enhavo/media/media-library/Components/ApplicationComponent.vue'));
})();
