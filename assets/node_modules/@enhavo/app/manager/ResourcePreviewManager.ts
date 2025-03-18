import {ActionManager} from "@enhavo/app/action/ActionManager";
import {ActionInterface} from "@enhavo/app/action/ActionInterface";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {PreviewData} from "@enhavo/app/action/model/PreviewAction";
import morphdom from 'morphdom';
import {UiManager} from "@enhavo/app/ui/UiManager";
import {ClientInterface} from "@enhavo/app/client/ClientInterface";

export class ResourcePreviewManager
{
    private abortController: AbortController = null;
    private initLoad = false;
    private initWait = false;
    private initWaitData: PreviewData = null;

    public frameClass: string;
    public iframe: HTMLIFrameElement;

    public actions: ActionInterface[];
    public actionsSecondary: ActionInterface[];

    constructor(
        private readonly actionManager: ActionManager,
        private readonly frameManager: FrameManager,
        private readonly uiManager: UiManager,
        private readonly client: ClientInterface,
    ) {
    }

    async load(url: string)
    {
        const transport = await this.client.fetch(url);

        if (!transport.ok || !transport.response.ok) {
            this.frameManager.loaded();
            await this.client
                .handleError(transport, {
                    terminate: true,
                });
            return;
        }

        const data = await transport.response.json();

        this.actions = this.actionManager.createActions(data.actions);
        this.actionsSecondary = this.actionManager.createActions(data.actionsSecondary);

        this.subscribe();

        this.frameManager.loaded();
    }

    loadDefaults()
    {
        this.actions = this.actionManager.createActions([
            {
                key: 'desktop',
                label: 'Desktop',
                icon: 'desktop_windows',
                model: 'PreviewModeAction',
                component: 'action-action',
                frameClass: 'desktop'
            },
            {
                key: 'mobile',
                label: 'Mobile',
                icon: 'phone_iphone',
                model: 'PreviewModeAction',
                component: 'action-action',
                frameClass: 'mobile'
            },
            {
                key: 'tablet',
                label: 'Tablet',
                icon: 'tablet_mac',
                model: 'PreviewModeAction',
                component: 'action-action',
                frameClass: 'tablet'
            },
        ]);

        this.subscribe();

        this.actionsSecondary = [];
        this.frameManager.loaded();
    }

    private subscribe()
    {
        this.uiManager.loading(true);
        this.frameManager.on('preview-data', async (event) => {
            let previewData = (event as PreviewData);
            if ((event as PreviewData).target == this.frameManager.getId()) {
                if (!this.initLoad) {
                    this.initLoad = true;
                    this.initWait = true;
                    await this.initPreview(previewData);
                    this.initWait = false;

                    if (this.initWaitData) {
                        await this.refreshPreview(previewData);
                        this.initWaitData = null;
                    }
                } else if (this.initWait) {
                    this.initWaitData = previewData;
                } else {
                    await this.refreshPreview(previewData);
                }

                event.resolve();
            }
        })
    }

    private initPreview(data: PreviewData): Promise<void>
    {
        this.uiManager.loading(true);
        const form = this.createForm(data)

        return new Promise((resolve, reject) => {
            this.iframe.addEventListener('load', () => {
                resolve();
                this.uiManager.loading(false);
            });

            document.body.appendChild(form);
            form.submit();
        })
    }

    private createForm(data: PreviewData)
    {
        let form = document.createElement("form");
        form.method = "POST";
        form.action = data.url;
        form.target = this.iframe.name;

        let formData = new URLSearchParams(data.formData)
        for (let singleData of formData) {
            let input = document.createElement("input");
            input.type = 'hidden';
            input.value = singleData[1];
            input.name = singleData[0];
            form.appendChild(input);
        }

        return form;
    }

    private async refreshPreview(data: PreviewData): Promise<void>
    {
        if (data.forceReload) {
            await this.initPreview(data);
            return;
        }

        const htmlData = await this.fetchData(data);
        const htmlElements = this.parseHTML(htmlData);

        for (let htmlElement of htmlElements) {
            if (typeof htmlElement['querySelector'] != 'function') {
                continue;
            }

            let inspectElement = htmlElement as HTMLElement

            for (let selector of data.selectors) {
                if (inspectElement.matches(selector)) {
                    this.replaceHtml(selector, inspectElement);
                    continue;
                }

                let element = inspectElement.querySelector(selector);
                if (element) {
                    this.replaceHtml(selector, inspectElement);
                }
            }
        }
    }

    async fetchData(data: PreviewData): Promise<string>
    {
        if (this.abortController !== null) {
            this.abortController.abort();
        }

        this.abortController = new AbortController();

        const transport = await this.client.fetch(data.url, {
            method: 'POST',
            body: data.formData,
            signal: this.abortController.signal,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        });

        this.abortController = null;

        if (!transport.ok || !transport.response.ok) {
            this.frameManager.loaded();
            await this.client
                .handleError(transport, {
                    abortable: true,
                });
            return null;
        }

        return await transport.response.text();
    }

    private parseHTML(str: string)
    {
        const tmp = document.implementation.createHTMLDocument('');
        tmp.body.innerHTML = str;
        return [...tmp.body.childNodes];
    }

    private replaceHtml(selector: string, newElement: HTMLElement)
    {
        let oldElement = this.iframe.contentWindow.document.querySelector(selector);
        morphdom(oldElement, newElement);
    }
}
