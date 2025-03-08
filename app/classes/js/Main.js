import UserHandler from "./UserHandler.js";
import Init from "./Init.js";
import CheckHash from "./CheckHash.js";
import Router from "./Router.js";
export default class Main {
    constructor() {
        const userHandler = new UserHandler();
        const init = new Init(userHandler);

        const root = document.getElementById('root');
        const checkHash = new CheckHash(root, init);
        const router = new Router(checkHash);

        this.initApp(init, checkHash, router);
    }

    initApp(init, checkHash, router) {
        checkHash.checker(window.location.hash);

        const home = document.querySelector('.home');
        home.addEventListener('click', () => {
            window.location.hash = '';
            checkHash.checker(window.location.hash);
        })

        window.addEventListener('hashchange', () => {
            router.getHash();
        });
    }


}