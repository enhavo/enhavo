import FormType from "@enhavo/app/form/FormType";
import EventDispatcher from "@enhavo/app/frame/EventDispatcher";
import $ from "jquery";
import LoadGlobalDataEvent from "@enhavo/app/frame/event/LoadGlobalDataEvent";
import SaveGlobalDataEvent from "@enhavo/app/frame/event/SaveGlobalDataEvent";
import DataStorageEntry from "@enhavo/app/frame/DataStorageEntry";

export default class TranslationType extends FormType
{
    private locales: string[];
    private currentLocale: string;
    private eventDispatcher: EventDispatcher;

    constructor(element:HTMLElement, eventDispatcher: EventDispatcher)
    {
        super(element);
        this.locales = this.$element.data('translation-type');
        this.eventDispatcher = eventDispatcher;
        this.currentLocale = this.locales[0];

        this.initListener();
        this.showWidget();
        this.showCurrentLocale();
        this.showLocaleSwitch();

        $(document).on('switchLanguage', (event, locale) => {
            this.currentLocale = locale;
            this.showWidget();
            this.showCurrentLocale();
            this.showLocaleSwitch();
        });

        this.eventDispatcher.dispatch(new LoadGlobalDataEvent('translation-locale')).then((data: DataStorageEntry) => {
            if(data) {
                if(data.value != this.currentLocale) {
                    this.switchLocale(data.value);
                }
            }
        });
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
        $(document).trigger('switchLanguage', [locale]);
        this.eventDispatcher.dispatch(new SaveGlobalDataEvent('translation-locale', locale));
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
