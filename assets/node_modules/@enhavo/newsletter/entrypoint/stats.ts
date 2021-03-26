import Container from "@enhavo/dependency-injection"
(async () => {
    await Container.init();
    (await Container.get('@enhavo/newsletter/stats/StatsApp')).init();
    (await Container.get('@enhavo/app/vue/VueApp')).init('app', await Container.get('@enhavo/newsletter/stats/components/IndexComponent.vue'));
})();
