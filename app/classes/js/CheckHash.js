export default class CheckHash {
    root;

    constructor(root, init) {
        this.root = root;
        this.init = init;
    }

    checker(hash) {
        if (hash === '') {
            fetch('/app/templates/home.php')
                .then(response => response.text())
                .then(html => {
                    this.root.innerHTML = html;
                    this.init.homeInit();
                })
                .catch(error => console.log(error))
        }
        if (session_data.user_id) {
            if (hash === "#cinemas") {
                fetch('/app/templates/cinemas.php')
                    .then((response) => response.text())
                    .then((html) => {
                        this.root.innerHTML = html;
                        this.init.cinemasInit();
                    })
                    .catch((error) => console.log(error));
            }
            if (hash.startsWith("#movies?id=")) {
                const cinemaId = hash.split("=")[1];
                fetch(`/app/templates/movies.php?id=${cinemaId}`)
                    .then((response) => response.text())
                    .then((html) => {
                        this.root.innerHTML = html;
                        this.init.moviesInit();
                    })
                    .catch((error) => console.log(error));
            }
            if (hash.startsWith("#screenings?id=")) {
                const movieId = hash.split("=")[1];
                fetch(`/app/templates/screenings.php?id=${movieId}`)
                    .then((response) => response.text())
                    .then((html) => {
                        this.root.innerHTML = html;
                        this.init.screeningsInit();
                    })
                    .catch((error) => console.log(error));
            }
            if (hash.startsWith("#hall?id=")) {
                const paramsString = hash.split('?')[1];
                const params = new URLSearchParams(paramsString);

                const id = params.get('id');
                const hall = params.get('hall');
                const movie = params.get('movie');
                const price = params.get('price');
                const screening = params.get('screening');
                fetch(`/app/templates/hall.php?id=${id}&hall=${hall}&movie=${movie}&price=${price}&screening=${screening}`)
                    .then((response) => response.text())
                    .then((html) => {
                        this.root.innerHTML = html;
                        this.init.hallInit(price, session_data, screening);
                    })
                    .catch((error) => console.log(error));
            }
            if (hash === "#profile") {
                fetch('/app/templates/profile.php')
                    .then(response => response.text())
                    .then(html => {
                        this.root.innerHTML = html;
                        this.init.profileInit();
                    })
                    .catch(error => console.log(error));
            }
            if (admin) {
                if (hash === "#admin_panel") {
                    fetch('/app/templates/admin.php')
                        .then(response => response.text())
                        .then(html => {
                            this.root.innerHTML = html;
                            this.init.adminInit();
                        })
                        .catch(error => console.log(error))
                }
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