import Container from "@enhavo/dependency-injection"

(async () => {
    await Container.init();
    (await Container.get('@enhavo/app/Index/IndexApp')).init();
    (await Container.get('@enhavo/app/Vue/VueApp')).init('app', await Container.get('@enhavo/app/Index/Components/IndexComponent.vue'));
})();
