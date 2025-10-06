

export class TranslationManager
{
    public locale: string;
    public locales: string[];


    public changeLocale(locale: string): void
    {
        this.locale = locale;
        localStorage.setItem('translation_locale', this.locale);
    }

    public initLocales(locales: string[])
    {
        if (this.locales == null) {
            this.locales = locales;

            window.addEventListener('storage', (event) => {
                if (event.key == 'translation_locale') {
                    this.locale = event.key;
                }
            });

            let value = window.localStorage.getItem('translation_locale');
            if (value != null && locales.includes(value)) {
                this.locale = value;
            } else {
                this.locale = locales[0]
                localStorage.setItem('translation_locale', this.locale);
            }
        }
    }
}
