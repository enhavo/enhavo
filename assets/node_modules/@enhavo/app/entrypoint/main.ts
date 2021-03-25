import Container from "@enhavo/dependency-injection"

(async () => {
    await Container.init();
    (await Container.get('@enhavo/app/main/MainApp')).init();
    (await Container.get('@enhavo/app/vue/VueApp')).init('app', await Container.get('@enhavo/app/main/components/MainComponent.vue'));
})();
