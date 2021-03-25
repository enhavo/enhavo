import Container from "@enhavo/dependency-injection"

(async () => {
    await Container.init();
    (await Container.get('@enhavo/dashboard/dashboard/DashboardApp')).init();
    (await Container.get('@enhavo/app/vue/VueApp')).init('app', await Container.get('@enhavo/dashboard/dashboard/components/ApplicationComponent.vue'));
})();
