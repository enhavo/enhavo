/*<![CDATA[*/
(function() {
    "use strict";

    function generateId() {
        return "10000000-1000-4000-8000-100000000000".replace(/[018]/g, c =>
            (+c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> +c / 4).toString(16)
        );
    }

    const url = new URL(window.location != window.parent.location ? document.referrer : document.location.href);
    let port = url.port ? ':' +  url.port : '';
    let target = url.protocol + '//' + url.hostname + port;

    const event = {
        name: 'frame_loaded',
        origin: window.name,
        id: window.name,
        uuid: generateId(),
        ttl: 3,
        history: []
    };

    window.parent.postMessage('frame_stack_event|' + JSON.stringify(event), target);
})();
/*]]>*/
