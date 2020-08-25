import Container from "@enhavo/dependency-injection"

(async () => {
    await Container.init();
    (await Container.get('@enhavo/dashboard/Dashboard/DashboardApp')).init();
    (await Container.get('@enhavo/app/Vue/VueApp')).init('app', await Container.get('@enhavo/dashboard/Dashboard/Components/ApplicationComponent.vue'));
})();
