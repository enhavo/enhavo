import Container from "@enhavo/dependency-injection"

(async () => {
    await Container.init();
    (await Container.get('@enhavo/app/list/ListApp')).init();
    (await Container.get('@enhavo/app/vue/VueApp')).init('app', await Container.get('@enhavo/app/list/components/ListApplicationComponent.vue'));
})();
