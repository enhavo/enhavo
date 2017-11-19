declare module "app/Translator"
{
    const translator: {
        trans(key:string): string;
    };

    export = translator;
}