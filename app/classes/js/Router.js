export default class Router {
    hash;
    root;
    links;

    constructor(checkHash) {
        this.hash = window.location.hash;
        this.root = document.getElementById('root');
        this.links = document.querySelectorAll('.nav-link');
        this.checkHash = checkHash;
    }

    getHash() {
        this.hash = window.location.hash;
        this.checkHash.checker(this.hash);
    }
}