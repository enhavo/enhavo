import {WysiwygConfigInterface} from "@enhavo/form/wysiwyg/WysiwygConfigInterface";

export class WysiwygConfigRegistry
{
    private configs: Map<string, WysiwygConfigInterface> = new Map<string, WysiwygConfigInterface>();

    public register(config: WysiwygConfigInterface): WysiwygConfigRegistry
    {
        this.configs.set(config.name, config);

        return this;
    }

    public getConfig(name: string): WysiwygConfigInterface
    {
        if (this.configs.has(name)) {
            return this.configs.get(name);
        }
        return null;
    }
}
