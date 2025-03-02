export default class UserHandler {
    formHandler;

    constructor() {
        this.formHandler = './app/formHandler.php';
    }

    createUser() {
        const name = document.getElementById('reg_name').value;
        const email = document.getElementById('reg_email').value;
        const password = document.getElementById('reg_password').value;
        const header_error = document.getElementById('reg__error');

        fetch(this.formHandler, {
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
            .then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                    location.reload();
                }
                if (data.error) {
                    if (header_error) {
                        header_error.style.display = "block";
                        header_error.textContent = data.error;
                    } else {
                        console.log('non error');
                    }
                } else {
                    console.log('non data error');
                }
            })
            .catch((error) => console.log(error))
    }

    readUser() {
        const email = document.getElementById('auth_email').value;
        const password = document.getElementById('auth_password').value;
        const header_error = document.getElementById('auth__error');

        fetch(this.formHandler, {
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
                    location.reload();
                }
                if (data.error) {
                    if (header_error) {
                        header_error.style.display = "block";
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
}