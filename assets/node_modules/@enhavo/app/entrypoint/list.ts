import Container from "@enhavo/dependency-injection"

(async () => {
    await Container.init();
    (await Container.get('@enhavo/app/List/ListApp')).init();
    (await Container.get('@enhavo/app/Vue/VueApp')).init('app', await Container.get('@enhavo/app/List/Components/ListApplicationComponent.vue'));
})();
