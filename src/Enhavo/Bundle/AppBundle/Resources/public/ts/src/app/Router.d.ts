declare module "app/Router"
{
    const router: {
        generate(route:string, parameters:object): string;
        generate(route:string, parameters:object, type:string): string;
    };

    export = router;
}