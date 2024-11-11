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

    morphTabs(targets: TabInterface[], sources: TabInterface[]): TabInterface[]
    {
        for (let target of targets) {
            let found = false;
            for (let source of sources) {
                if (target.key && source.key === target.key) {
                    target.morph(source);
                    found = true;
                    break;
                }
            }
            if (!found) {
                targets.splice(targets.indexOf(target), 1);
            }
        }

        for (let source of sources) {
            let found = false;
            for (let target of targets) {
                if (source.key && source.key === target.key) {
                    found = true;
                    break;
                }
            }
            if (!found) {
                targets.push(source);
            }
        }

        return targets;
    }
}
