export default class AuthValid {
    fields;
    btn;
    errors;

    constructor() {
        this.fields = [
            {
                email: document.getElementById('auth_email'),
                regex: /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i,
                validate: function () {
                    if (!this.email.value.length && this.email.blur) {
                        return "Поле не может быть пустым!";
                    } else if(!this.regex.test(this.email.value)) {
                        return "Некорректный email!";
                    } else {
                        return "";
                    }
                }
            },
            {
                password: document.getElementById('auth_password'),
                validate: function () {
                    if (!this.password.value.length && this.password.blur) {
                        return 'Поле не должно быть пустым!';
                    } else {
                        return "";
                    }
                }
            }
        ]
        this.validate();
    }

    renderError(field, error) {
        const form_error = field.closest('.form__label').querySelector('.form__error');
        if (form_error) {
            form_error.textContent = error;
        }
        this.checkError();
    }

    checkError() {
        this.btn = document.getElementById('auth_submit');
        this.errors = document.querySelectorAll('.form__error');
        let hasError = false;
        if (this.btn) {
            this.errors.forEach(error => {
                if (error.textContent.trim() !== "") {
                    hasError = true;
                }
            })
            this.btn.disabled = hasError;
        }
    }

    validate() {
        this.fields.forEach(field => {
            if (field.email) {
                field.email.addEventListener('input', () => {
                    this.renderError(field.email, field.validate())
                })
                field.email.addEventListener('blur', () => {
                    field.email.blur = true;
                    this.renderError(field.email, field.validate())
                })
            }
            if (field.password) {
                field.password.addEventListener('input', () => {
                    this.renderError(field.password, field.validate())
                })
                field.password.addEventListener('blur', () => {
                    field.password.blur = true;
                    this.renderError(field.password, field.validate())
                })
            }
        })
    }
}