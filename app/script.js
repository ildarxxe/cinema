document.addEventListener('DOMContentLoaded', () => {
    let hash = window.location.hash;
    checkHash(hash);
    const root = document.getElementById('root');

    const links = document.querySelectorAll('.nav-link');
    links.forEach(link => {
        link.addEventListener('click', () => {
            setTimeout(getHash, 1)
        })
    })

    function getHash() {
        hash = window.location.hash;
        checkHash(hash);
    }

    function checkHash(hash) {
        if (hash === "#auth") {
            fetch("/app/templates/auth.php")
                .then((response) => response.text())
                .then((html) => {
                    root.innerHTML = html;
                    authInit();
                })
                .catch((error) => console.log(error));
        }
        if (hash === "#reg") {
            fetch('/app/templates/reg.php')
                .then((response) => response.text())
                .then((html) => { root.innerHTML = html; regInit() })
                .catch((error) => console.log(error));
        }
        if (hash === "#cinema") {
            fetch('/app/templates/cinema.php')
                .then((response) => response.text())
                .then((html) => {
                    root.innerHTML = html;
                    cinemaInit()
                })
                .catch((error) => console.log(error));
        }
        if (hash === "#logout") {
            fetch('/app/templates/logout.php')
                .then((response) => response.text())
                .then((html) => {
                    root.innerHTML = html;
                })
                .catch((error) => console.log(error));
        }
    }

    function regInit() {
        const btn = document.getElementById('reg_submit');
        if (btn) {
            btn.addEventListener('click', createUser)
        } else {
            console.log('btn reg_submit not found');
        }
    }

    function authInit() {
        const btn = document.getElementById('auth_submit');
        if (btn) {
            btn.addEventListener('click', readUser);
        } else {
            console.log('btn auth_submit not found');
        }
    }

    function cinemaInit() {
        const cinema_content = document.querySelector('.cinema__content');
        for (let i = 1; i <= 36; i++) {
            const cell = document.createElement('div');
            cell.classList.add('cell');

            cell.setAttribute("data-set", `${i}`);
            cell.innerHTML = i;
            cinema_content.appendChild(cell);
        }

        // const cells = document.querySelectorAll('.cell');
        // cells.forEach(cell => {
        //     cell.addEventListener('click', () => {
        //         console.log(cell.dataset.set)
        //     })
        // })
    }

    const formHandler = '/app/formHandler.php';

    function createUser() {
        const name = document.getElementById('reg_name').value;
        const email = document.getElementById('reg_email').value;
        const password = document.getElementById('reg_password').value;

        fetch(formHandler, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                action: 'create',
                name,
                email,
                password
            })
        })
            .then(response => response.json())
            .then(data => data)
            .catch((error) => console.log(error))
    }

    function readUser() {
        const email = document.getElementById('auth_email').value;
        const password = document.getElementById('auth_password').value;
        const header_error = document.getElementById('header__error');
        fetch(formHandler, {
            method: "POST",
            header: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                action: "read",
                email,
                password
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
                if (data.error) {
                    if (header_error) {
                        header_error.textContent = data.error;
                    } else {
                        console.log('non error');
                    }
                } else {
                    console.log('non data.error')
                }
            })
            .catch((error) => console.log(error))
    }

})