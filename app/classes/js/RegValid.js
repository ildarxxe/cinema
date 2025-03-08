export default class RegValid {
    fields;
    btn;
    errors;

    constructor() {
        let password_value = null;
        this.fields = [
            {
                name: document.getElementById('reg_name'),
                validate: function () {
                    if (!this.name.value.length && this.name.blur) {
                        return "Поле не должно быть пустым!";
                    } else {
                        return "";
                    }
                }
            },
            {
                email: document.getElementById('reg_email'),
                regex: /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i,
                validate: function () {
                    if (!this.email.value.length && this.email.blur) {
                        return "Поле не должно быть пустым!";
                    } else if (!this.regex.test(this.email.value)) {
                        return "Некорректный email";
                    } else {
                        return "";
                    }
                }
            },
            {
                password: document.getElementById('reg_password'),
                validate: function () {
                    if (!this.password.value.length && this.password.blur) {
                        password_value = this.password.value;
                        return 'Поле не должно быть пустым!';
                    } else if (this.password.value.length < 8 && this.password.blur) {
                        password_value = this.password.value;
                        return "Пароль должен содержать не менее 8 символов";
                    } else {
                        password_value = this.password.value;
                        return "";
                    }
                }
            },
            {
                password_confirm: document.getElementById('password_confirm'),
                validate: function () {
                    if (!this.password_confirm.value.length && this.password_confirm.blur) {
                        return "Поле не должно быть пустым!";
                    } else if (this.password_confirm.value !== password_value) {
                        return "Пароли не совпадают!";
                    } else {
                        return "";
                    }
                }
            }
        ];
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
        this.btn = document.getElementById('reg_submit');
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
            if (field.name) {
                field.name.addEventListener('input', () => {
                    this.renderError(field.name, field.validate())
                })
                field.name.addEventListener('blur', () => {
                    field.name.blur = true;
                    this.renderError(field.name, field.validate())
                })
            }
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
            if (field.password_confirm) {
                field.password_confirm.addEventListener('input', () => {
                    this.renderError(field.password_confirm, field.validate())
                })
                field.password_confirm.addEventListener('blur', () => {
                    field.password_confirm.blur = true;
                    this.renderError(field.password_confirm, field.validate())
                })
            }
        })
    }
}