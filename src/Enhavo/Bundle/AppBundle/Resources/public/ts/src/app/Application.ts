import * as $ from 'jquery'

class Application
{
    private $application: JQuery;
    private $overlayStack: JQuery;

    constructor(element: HTMLElement)
    {
        this.$application = $(element);
        this.$overlayStack = this.$application.find('[data-view-overlay-stack]');
    }

    public pushOverlayView(option: OverlayOption)
    {
        this.$overlayStack.height('100%');
        if(option == null) {
            option = new OverlayOption;
        }
        let overlay = new Overlay(this, option);
        this.$overlayStack.append(overlay.getHtml());
        return overlay;
    }

    public handleRemoveOverlay()
    {
        if(this.$overlayStack.children().length == 0) {
            this.$overlayStack.height('0');
        }
    }
}

export class OverlayOption
{
    init: (overlay: Overlay) => void;
    close: (overlay: Overlay) => void;
    closeSelector: string = '[data-overlay-close]';
}

export class Overlay
{
    private application: Application;

    private $element: JQuery;

    private $content: JQuery;

    private $loading: JQuery;

    private option: OverlayOption;

    constructor(application: Application, option: OverlayOption)
    {
        let html = $.parseHTML('<div class="overlay-view"><div class="overlay-loading" data-overlay-loading><i class="loading-icon icon-spinner icon-spin"></i></div><div class="overlay-background"></div><div class="overlay-content" data-overlay-content></div></div>')[0];
        this.$element = $(html);
        this.$content = this.$element.find('[data-overlay-content]');
        this.$loading = this.$element.find('[data-overlay-loading]');
        this.option = option;
        this.application = application;
    }

    private init()
    {
        let self = this;
        this.$content.find(this.option.closeSelector).on('click', function() {
            self.close();
        });

        if(typeof this.option.init == 'function') {
            this.option.init(this);
        }
    }

    public setContent(html:string|HTMLElement): Overlay
    {
        this.$content.html(html);
        this.init();
        return this;
    }

    public getHtml(): HTMLElement
    {
        return this.$element.get(0);
    }

    public wait(wait: (callback: () => void, overlay:Overlay) => void): Overlay
    {
        this.$loading.show();
        let self = this;
        wait(function() {
            self.$loading.hide();
        }, this);
        return this;
    }

    public closeSelector(selector: string): Overlay
    {
        this.option.closeSelector = selector;
        return this;
    }

    public onClose(callback: (overlay: Overlay) => void): Overlay
    {
        this.option.close = callback;
        return this;
    }

    public onInit(callback: (overlay: Overlay) => void): Overlay
    {
        this.option.init = callback;
        return this;
    }

    public close()
    {
        if(typeof this.option.close == 'function') {
            this.option.close(this);
        }
        this.$element.remove();
        this.application.handleRemoveOverlay();
    }
}

let application = new Application(document.body);
export default application;