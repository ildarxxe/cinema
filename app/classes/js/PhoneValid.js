export default class PhoneValid {
    fields;
    btn;
    errors;

    constructor() {
        this.fields = document.querySelectorAll('.input__phone');
        // this.btn = document.getElementById('reg_submit') ?? document.querySelector('button');
        this.errors = document.querySelectorAll('.form__error');
        this.init();
    }

    init() {
        const regex = /^[\+]?[0-9]{0,3}\W?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
        let fieldValid = [];
        this.fields.forEach(field => {
            let name = field.name;
            fieldValid.push({
                name: field,
                validate: function () {
                    if (!this.name.value.length && this.name.blur) {
                        return 'Поле не может быть пустым';
                    } else if (!regex.test(this.name.value)) {
                        return 'Некорректный номер телефона';
                    } else {
                        return '';
                    }
                }
            })
        })
        this.validation(fieldValid);
    }

    renderError(field, error) {
        const error_box = field.closest('.form__label').querySelector('.form__error');
        if (error_box) {
            error_box.innerHTML = error;
        }
    }

    validation(fields) {
        fields.forEach(field => {
            field.name.addEventListener('input', () => {
                this.renderError(field.name, field.validate());
            })
            field.name.addEventListener('blur', () => {
                field.name.blur = true;
                this.renderError(field.name, field.validate());
            })
        })
    }
}