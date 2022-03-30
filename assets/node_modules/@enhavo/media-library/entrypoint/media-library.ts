import Container from "@enhavo/dependency-injection";
import '@enhavo/media-library/assets/styles.scss';

(async () => {
    await Container.init();
    (await Container.get('@enhavo/media-library/MediaLibraryApp')).init();
    (await Container.get('@enhavo/app/vue/VueApp')).init('app', await Container.get('@enhavo/media-library/components/MediaLibraryComponent.vue'));
})();
