import Container from "@enhavo/dependency-injection"

(async () => {
    await Container.init();
    (await Container.get('@enhavo/app/index/IndexApp')).init();
    (await Container.get('@enhavo/app/vue/VueApp')).init('app', await Container.get('@enhavo/app/index/components/IndexComponent.vue'));
})();
