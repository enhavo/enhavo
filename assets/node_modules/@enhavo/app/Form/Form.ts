import * as $ from 'jquery';
import * as _ from "lodash";
import Tab from "@enhavo/app/Form/Tab";
import FormData from "@enhavo/app/Form/FormData";

export default class Form
{
    private tabs: Tab[];
    private data: FormData;

    constructor(data: FormData)
    {
        this.data = _.extend(data, new FormData);
        for (let i in data.tabs) {
            _.extend(data.tabs[i], new Tab);
        }
        this.tabs = data.tabs;
    }

    public load()
    {
        for(let tab of this.tabs) {
            if(!this.data.tab) {
                this.data.tab = tab.key;
            }
            tab.element = <HTMLElement>$('[data-tab-container]').find('[data-tab='+tab.key+']')[0];
        }
    }

    public changeTab(key: string)
    {
        this.data.tab = key;
    }
}