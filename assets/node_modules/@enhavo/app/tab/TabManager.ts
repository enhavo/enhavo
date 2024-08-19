import {TabInterface} from "@enhavo/app/tab/TabInterface";
import {TabFactory} from "@enhavo/app/tab/TabFactory";

export class TabManager
{
    constructor(
        private readonly factory: TabFactory,
    ) {
    }

    createTabs(tabs: object[]): TabInterface[]
    {
        let data = [];
        for (let i in tabs) {
            data.push(this.createTab(tabs[i]));
        }
        return data;
    }

    createTab(tab: object): TabInterface
    {
        if (!tab.hasOwnProperty('model')) {
            throw 'The tab data needs a "model" property!';
        }

        return this.factory.createWithData(tab['model'], tab);
    }
}
