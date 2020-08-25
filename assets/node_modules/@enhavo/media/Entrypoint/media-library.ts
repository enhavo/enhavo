import Container from "@enhavo/dependency-injection"
(async () => {
    await Container.init();
    (await Container.get('@enhavo/media/MediaLibrary/MediaLibraryApp')).init();
    (await Container.get('@enhavo/app/Vue/VueApp')).init('app', await Container.get('@enhavo/media/MediaLibrary/Components/ApplicationComponent.vue'));
})();
