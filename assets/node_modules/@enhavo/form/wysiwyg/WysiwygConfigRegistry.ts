import {WysiwygConfigInterface} from "@enhavo/form/wysiwyg/WysiwygConfigInterface";

export default class WysiwygConfigRegistry
{
    private configs: WysiwygConfigInterface[] = [];

    public register(config: WysiwygConfigInterface): WysiwygConfigRegistry
    {
        this.configs.push(config);

        return this;
    }

    public getConfig(name: string): WysiwygConfigInterface
    {
        for (let type of this.configs) {
            if (name === type.name) {
                return type;
            }
        }
        return null;
    }
}
