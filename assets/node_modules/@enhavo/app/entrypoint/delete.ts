import Container from "@enhavo/dependency-injection"

(async () => {
    await Container.init();
    (await Container.get('@enhavo/app/Delete/DeleteApp')).init();
    (await Container.get('@enhavo/app/Vue/VueApp')).init('app', await Container.get('@enhavo/app/Delete/Components/DeleteComponent.vue'));
})();
