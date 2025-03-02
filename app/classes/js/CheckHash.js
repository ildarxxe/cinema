export default class CheckHash {
    root;

    constructor(root, init) {
        this.root = root;
        this.init = init;
    }

    checker(hash) {
        if (session_data.user_id) {
            if (hash === "#cinema") {
                fetch('/app/templates/cinema.php')
                    .then((response) => response.text())
                    .then((html) => {
                        this.root.innerHTML = html;
                        this.init.cinemaInit()
                    })
                    .catch((error) => console.log(error));
            }
        } else {
            if (hash === "#auth") {
                fetch("/app/templates/auth.php")
                    .then((response) => response.text())
                    .then((html) => {
                        this.root.innerHTML = html;
                        this.init.authInit();
                    })
                    .catch((error) => console.log(error));
            }
            if (hash === "#reg") {
                fetch('/app/templates/reg.php')
                    .then((response) => response.text())
                    .then((html) => {
                        this.root.innerHTML = html;
                        this.init.regInit()
                    })
                    .catch((error) => console.log(error));
            }
        }
    }
}