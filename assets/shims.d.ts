import { DefineComponent } from "vue";

declare module "*.vue" {
    const component: DefineComponent<{}, {}, any>;
    export default component;
}
