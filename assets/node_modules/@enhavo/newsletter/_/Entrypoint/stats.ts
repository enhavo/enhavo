import Container from "@enhavo/dependency-injection"
(async () => {
    await Container.init();
    (await Container.get('@enhavo/newsletter/Stats/StatsApp')).init();
    (await Container.get('@enhavo/app/Vue/VueApp')).init('app', await Container.get('@enhavo/newsletter/Stats/Components/IndexComponent.vue'));
})();
