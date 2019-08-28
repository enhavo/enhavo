import FormType from "@enhavo/app/Form/FormType";
import * as $ from "jquery";

export default class TranslationType extends FormType
{
    private locales: string[];
    private currentLocale: string;

    constructor(element:HTMLElement)
    {
        super(element);
        this.locales = this.$element.data('translation-type');
        this.currentLocale = this.locales[0];
        this.initListener();
        this.showWidget();
        this.showCurrentLocale();
        this.showLocaleSwitch();
    }

    protected init() {}

    protected initListener()
    {
        let self = this;
        this.$element.find('[data-translation-switch]').click(function() {
            let locale = $(this).data('translation-switch');
            self.switchLocale(locale);
        });
    }

    private switchLocale(locale: string)
    {
        this.currentLocale = locale;
        this.showWidget();
        this.showCurrentLocale();
        this.showLocaleSwitch();
    }

    private showWidget()
    {
        this.$element.find('[data-translation-widget]').each((index, element) => {
            let $element = $(element);
            let locale = $element.data('translation-widget');
            if(locale == this.currentLocale) {
                $element.show();
            } else {
                $element.hide();
            }
        });
    }

    private showCurrentLocale()
    {
        this.$element.find('[data-translation-switcher-current]').html(this.currentLocale);
    }

    private showLocaleSwitch()
    {
        this.$element.find('[data-translation-switch]').each((index, element) => {
            let $element = $(element);
            let locale = $element.data('translation-switch');
            if(locale == this.currentLocale) {
                $element.hide();
            } else {
                $element.show();
            }
        });
    }
}
