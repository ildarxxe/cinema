export default class UserHandler {
    formHandler;

    constructor() {
        this.formHandler = './app/formHandler.php';
    }

    createUser() {
        const name = document.getElementById('reg_name').value;
        const email = document.getElementById('reg_email').value;
        const password = document.getElementById('reg_password').value;
        const phones = document.querySelectorAll('.input__phone');
        const header_error = document.getElementById('reg__error');

        const phones_arr = [];
        phones.forEach(phone => {
            phones_arr.push(phone.value);
        })

        fetch(this.formHandler, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                action: 'create',
                name,
                email,
                password,
                phones_arr
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

    putUser(action) {
        if (action === "put_profile") {
            const change_name = document.getElementById('change_name').value;
            const change_email = document.getElementById('change_email').value;
            const phones_input = document.querySelectorAll('.input__phone');

            const phones = [];
            phones_input.forEach(phone => {
                phones.push(phone.value);
            })

            fetch(this.formHandler, {
                method: "PUT",
                header: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    action: "put_profile",
                    change_name,
                    change_email,
                    phones
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        alert(data.message);
                        window.location.href = data.redirect;
                        location.reload();
                    }
                })
                .catch(error => console.log(error))
        }
        if (action === "put_password") {
            const current_password = document.getElementById('current_password').value;
            const change_password = document.getElementById('change_password').value;

            fetch(this.formHandler, {
                method: "PUT",
                header: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    action: "put_password",
                    current_password,
                    change_password
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    }
                    if (data.redirect) {
                        alert(data.message);
                        window.location.href = data.redirect;
                        location.reload();
                    }
                })
                .catch(error => console.log(error))
        }
    }

    deleteUser() {
        const delete_password = document.getElementById('delete_password').value;
        fetch(this.formHandler, {
            method: "DELETE",
            header: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                action: "delete",
                delete_password
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                }
                if (data.redirect) {
                    alert(data.message);
                    window.location.href = data.redirect;
                    location.reload();
                }
            })
            .catch(error => console.log(error))
    }
}