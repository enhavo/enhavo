import $ from 'jquery'
import Router from "@enhavo/core/Router";

export default class MenuSwitchTenantEventListener
{
    private alreadyRunning = false;
    private readonly router: Router;

    constructor(router: Router) {
        this.router = router;
    }

    public listen(): void
    {
        if (this.alreadyRunning) {
            return;
        }
        let self = this;
        $(document).on('tenantChange', function (event, data) {
            let uri = new URL(self.router.generate('enhavo_multi_tenancy_switch'), window.origin);
            uri.searchParams.set('tenant', data);
            uri.searchParams.set('redirect', window.location.href);
            window.location.href = uri + '';
        });
        this.alreadyRunning = true;
    }
}
