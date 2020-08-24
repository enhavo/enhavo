import '@enhavo/user/assets/styles/login.scss'

import Container from "@enhavo/dependency-injection"

(async () => {
    await Container.init();
    (await Container.get('@enhavo/user/Login/LoginApp')).init();
})();
