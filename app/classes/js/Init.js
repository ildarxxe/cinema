import AuthValid from "./AuthValid.js";
import RegValid from "./RegValid.js";
import PhoneValid from "./PhoneValid.js";

export default class Init {
    countSeats;
    tickets;
    sum;

    constructor(userHandler) {
        this.userHandler = userHandler;
        this.countSeats = 200;
        this.tickets = [];
        this.sum = 0;
    }

    cinemasInit() {
        const links = document.querySelectorAll('.cinema__link');
        links.forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const key = link.closest('.cinema__card').dataset.key;
                window.location.hash = `#movies?id=${key}`;
            })
        })
    }

    moviesInit() {
        const cards = document.querySelectorAll('.movies__card');
        cards.forEach(card => {
            card.addEventListener('click', (e) => {
                e.preventDefault();
                const key = card.dataset.key;
                window.location.hash = `#screenings?id=${key}`;
            })
        })
    }

    screeningsInit() {
        const cards = document.querySelectorAll('.screenings__card');
        cards.forEach(card => {
            card.addEventListener('click', (e) => {
                e.preventDefault();
                const key = card.dataset.key;
                const hall = card.dataset.hall;
                const movie = card.dataset.movie;
                const price = card.dataset.price;
                const screening = card.dataset.screening;
                window.location.hash = `#hall?id=${key}&hall=${hall}&movie=${movie}&price=${price}&screening=${screening}`;
            })
        })
    }

    hallInit(price, session_data, screening) {
        const cells = document.querySelectorAll('.cell');
        const block_price = document.querySelector('.price__summ');
        block_price.innerHTML = this.sum;

        let get_tickets = null;

        async function getTickets() {
            try {
                const response = await fetch('./app/ticketsHandler.php', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        action: "getTickets",
                        screening
                    })
                });

                const data = await response.json();
                get_tickets = data.data;
                return get_tickets;
            } catch (error) {
                console.log(error);
            }
        }

        getTickets().then(r => {
            if (r) {
                const arr = r.split(', ');
                for (let i = 0; i < arr.length; i++) {
                    const elem = document.querySelector(`[data-cell="${arr[i]}"]`);
                    elem.classList.add('disabled');
                    elem.style.background = "rgba(27, 110, 27, 0.91)";
                }
            }
        });

        cells.forEach(cell => {
            cell.addEventListener('click', () => {
                if (!cell.classList.contains('disabled')) {
                    const index = cell.dataset.cell;
                    cell.classList.toggle('active');
                    this.saveTickets(index, cell);
                    this.sum = this.tickets.length * price;
                    block_price.innerHTML = this.sum;
                }
            })
        })

        const btn = document.querySelector('.buy_tickets');
        btn.addEventListener('click', (e) => {
            const price = this.sum;
            const tickets = this.tickets;
            const screening_id = screening;
            e.preventDefault();
            fetch('./app/ticketsHandler.php', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    action: "tickets",
                    price,
                    tickets,
                    screening_id
                })
            })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.log(error));
        })

    }

    saveTickets(index, cell) {
        if (cell.classList.contains('active')) {
            this.tickets.push(index);
        } else {
            this.tickets = this.tickets.filter(elem => elem !== String(index))
        }
    }

    regInit() {
        const btn = document.getElementById('reg_submit');
        if (btn) {
            btn.addEventListener('click', () => this.userHandler.createUser())
        } else {
            console.log('btn reg_submit not found');
        }

        const add_phone = document.querySelector('.add_phone');
        const phone_box = document.querySelector('.phones');
        let count = 2;

        add_phone.addEventListener('click', () => {
            const label = document.createElement('label');
            label.classList.add('form__label');
            label.classList.add('mb-3');
            label.classList.add('form__label--add');
            label.innerHTML = `Номер телефона: <div class="additional__phone"><input type="text" class="form-control input__phone" name="phone${count}" id="phone${count}"><button type="button" class="close_phone">-</button></div><span class="form__error"></span>`;

            const label_add = document.querySelectorAll('.form__label--add');
            if (label_add.length < 2) {
                count += 1;
                phone_box.appendChild(label);
            } else {
                const reg_error = document.getElementById('reg__error');
                reg_error.innerHTML = 'Максимальное количество номеров телефона - 3';
                reg_error.style.display = 'block';
            }

            closePhone();
        });

        function countPhones() {
            const labels = document.querySelectorAll('.form__label--add');
            labels.forEach((label, index) => {
                const newIndex = index + 2;
                const input = label.querySelector('input');

                input.id = `phone${newIndex}`;
                input.name = `phone${newIndex}`;
            });
            count = labels.length + 2;
        }

        function closePhone() {
            const close_phone = document.querySelectorAll('.close_phone');
            close_phone.forEach(btn => {
                btn.addEventListener('click', () => {
                    const label = btn.closest('.form__label--add');
                    label.remove();
                    countPhones();
                });
            });
        }

        new RegValid();
        new PhoneValid();
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.addedNodes) {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === Node.ELEMENT_NODE && node.classList.contains('form__label--add')) {
                            new PhoneValid();
                        }
                    })
                }
            })
        });

        observer.observe(document.querySelector('.phones'), {
            childList: true,
            subtree: true
        })
    }

    authInit() {
        const btn = document.getElementById('auth_submit');
        if (btn) {
            btn.addEventListener('click', () => this.userHandler.readUser());
        } else {
            console.log('btn auth_submit not found');
        }
        new AuthValid();
    }

    profileInit() {
        const edit_profile = document.querySelector('.edit_profile');
        const modal = document.querySelector('.form_edit_profile');
        const close_btns = document.querySelectorAll('.form__close');
        edit_profile.addEventListener('click', () => {
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        });
        const change_password = document.querySelector('.change_password');
        const change_password_modal = document.querySelector('.form_change_password');
        change_password.addEventListener('click', () => {
            if (change_password_modal.classList.contains('hidden')) {
                change_password_modal.classList.remove('hidden');
            } else {
                change_password_modal.classList.add('hidden');
            }
        })
        close_btns.forEach(btn => {
            btn.addEventListener('click', () => {
                const parent_elem = btn.closest('.form');
                parent_elem.classList.add('hidden');
            })
        })

        const edit_btn = document.querySelector('.edit_profile_submit');
        edit_btn.addEventListener('click', (e) => {
            e.preventDefault();
            this.userHandler.putUser("put_profile");
        })

        const change_password_btn = document.querySelector('.change_password_submit');
        change_password_btn.addEventListener('click', (e) => {
            e.preventDefault();
            this.userHandler.putUser("put_password");
        });

        const delete_profile = document.querySelector('.delete_profile');
        const delete_profile_submit = document.querySelector('.delete_profile_submit');
        delete_profile.addEventListener('click', () => {
            if (confirm('Вы точно хотите удалить аккаунт?')) {
                const form_delete_profile = document.querySelector('.form_delete_profile');
                if (form_delete_profile.classList.contains('hidden')) {
                    form_delete_profile.classList.remove('hidden');
                } else {
                    form_delete_profile.classList.add('hidden');
                }
                delete_profile_submit.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.userHandler.deleteUser();
                });
            }
        })

        const fields = [
            {
                password: document.getElementById('change_password'),
                validate: function () {
                    if (!this.password.value.length && this.password.blur) {
                        return 'Поле не должно быть пустым!';
                    } else if (this.password.value.length < 8 && this.password.blur) {
                        return "Пароль должен содержать не менее 8 символов";
                    } else {
                        return "";
                    }
                }
            },
            {
                current_password: document.getElementById('current_password'),
                validate: function () {
                    if (!this.current_password.value.length && this.current_password.blur) {
                        return 'Поле не может быть пустым!';
                    } else {
                        return '';
                    }
                }
            },
            {
                change_name: document.getElementById('change_name'),
                validate: function () {
                    if (!this.change_name.value.length && this.change_name.blur) {
                        return 'Поле не может быть пустым!';
                    } else {
                        return '';
                    }
                }
            },
            {
                change_email: document.getElementById('change_email'),
                regex: /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i,
                validate: function () {
                    if (!this.change_email.value.length && this.change_email.blur) {
                        return "Поле не может быть пустым!";
                    } else if (!this.regex.test(this.change_email.value)) {
                        return "Некорректный email!";
                    } else {
                        return "";
                    }
                }
            }
        ]
        validate();

        function renderError(field, error) {
            const form_error = field.closest('.form__label').querySelector('.form__error');
            if (form_error) {
                form_error.textContent = error;
            }
            checkError();
        }

        function checkError() {
            const btns = document.querySelectorAll('.profile__submit');
            const errors = document.querySelectorAll('.form__error');
            let hasError = false;
            if (btns) {
                btns.forEach(btn => {
                    errors.forEach(error => {
                        if (error.textContent.trim() !== "") {
                            hasError = true;
                        }
                    })
                    btn.disabled = hasError;
                })
            }
        }

        function validate() {
            fields.forEach(field => {
                if (field.password) {
                    field.password.addEventListener('input', () => {
                        renderError(field.password, field.validate())
                    })
                    field.password.addEventListener('blur', () => {
                        field.password.blur = true;
                        renderError(field.password, field.validate())
                    })
                }
                if (field.current_password) {
                    field.current_password.addEventListener('input', () => {
                        renderError(field.current_password, field.validate())
                    })
                    field.current_password.addEventListener('blur', () => {
                        field.current_password.blur = true;
                        renderError(field.current_password, field.validate())
                    })
                }
                if (field.change_name) {
                    field.change_name.addEventListener('input', () => {
                        renderError(field.change_name, field.validate())
                    })
                    field.change_name.addEventListener('blur', () => {
                        field.change_name.blur = true;
                        renderError(field.change_name, field.validate())
                    })
                }
                if (field.change_email) {
                    field.change_email.addEventListener('input', () => {
                        renderError(field.change_email, field.validate())
                    })
                    field.change_email.addEventListener('blur', () => {
                        field.change_email.blur = true;
                        renderError(field.change_email, field.validate())
                    })
                }
            })
        }

        const phones_box = document.querySelector('.phones');

        const inputs = document.querySelectorAll('.input__phone');
        const first_input = inputs[0];
        const box = first_input.closest('.additional__phone');
        const close_btn = box.querySelector('.close_phone');
        close_btn.remove();

        let count = inputs.length;
        function checkLengthInput() {
            const inputs = document.querySelectorAll('.input__phone');
            const add_btn = document.querySelector('.add_phone');

            count = inputs.length;
            console.log(add_btn);
            if (count >= 3) {
                if (add_btn) {
                    add_btn.style.display = 'none';
                }
            } else {
                if (add_btn) {
                    add_btn.style.display = 'block';
                }
            }

            let num = 0;
            inputs.forEach(input => {
                num++;
                const form_label = input.closest('.form__label');
                const span_count = form_label.querySelector('.count');
                span_count.textContent = `${num}`;
            })
        }

        checkLengthInput();

        function addInput() {
            const add_btn = document.querySelector('.add_phone');
            if (add_btn) {
                add_btn.addEventListener('click', () => {
                    count++;
                    const label = document.createElement('label');
                    label.classList.add('form__label');
                    label.classList.add('form__label--add');
                    label.innerHTML = `<div class="number_count">Номер телефона <span class="count"></span>:</div><div class="additional__phone"><input type="text" class="form-control input__phone" name="change_phone${count}"><button type="button" class="close_phone">-</button></div><span class="form__error"></span>`;
                    phones_box.appendChild(label);
                    checkLengthInput();
                })
            }
        }

        addInput();

        function removeInput() {
            const close_btn = document.querySelectorAll('.close_phone');
            close_btn.forEach(btn => {
                btn.addEventListener('click', () => {
                    const label = btn.closest('.form__label');
                    label.remove();
                    checkLengthInput();
                })
            })
        }

        removeInput();

        new PhoneValid();

        const phones = document.querySelector('.phones');
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.addedNodes) {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === Node.ELEMENT_NODE && node.classList.contains('form__label--add')) {
                            new PhoneValid();
                            removeInput();
                        }
                    })
                }
            })
        })

        observer.observe(phones, {
            childList: true,
            subtree: true
        });
    }

    adminInit() {
        if (admin) {
            const admin_cont = document.querySelector('.table__content');
            const links = document.querySelectorAll('.admin__link');
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    const table_name = e.target.dataset.tablename;
                    fetch('/app/templates/getTableForAdmin.php?table=' + table_name)
                        .then(response => response.text())
                        .then(html => {
                            admin_cont.innerHTML = html;
                        })
                        .catch(error => console.log(error));
                })
            })

            function deleteRow() {
                const delete_btn = document.querySelectorAll('.delete__row');
                delete_btn.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const id = btn.dataset.id;
                        const column = btn.dataset.value;
                        const table_name = btn.dataset.table;
                        fetch('/app/adminHandler.php', {
                            method: 'DELETE',
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                action: "delete",
                                id,
                                column,
                                table_name
                            })
                        })
                            .then(response => response.json())
                            .then(data => console.log(data))
                            .catch(error => console.log(error));
                    })
                })
            }

            function requestData(e) {
                e.preventDefault();
                const button = e.target;
                const form = button.closest('.admin__form');

                const table_name = button.dataset.table;
                const inputs = form.querySelectorAll('input');
                const inputs_obj = {};
                inputs.forEach(input => {
                    inputs_obj[input.name] = input.value;
                })
                fetch('/app/adminHandler.php', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        action: 'create',
                        table_name: table_name,
                        inputs: inputs_obj
                    })
                })
                    .then(response => response.json())
                    .then(data => data)
                    .catch(error => console.log(error))
            }

            const observer = new MutationObserver(mutations => {
                mutations.forEach(mutation => {
                    if (mutation.addedNodes) {
                        mutation.addedNodes.forEach(node => {
                            if (node.nodeType === Node.ELEMENT_NODE && node.classList.contains('table_box')) {
                                const td = document.querySelectorAll('td');

                                function wordLimit(elem, limit) {
                                    const text = elem.textContent.trim();
                                    const words = text.split(' ');
                                    if (words.length > limit) {
                                        elem.textContent = words.slice(0, limit).join(' ') + '...';
                                    }
                                }

                                td.forEach(elem => {
                                    wordLimit(elem, 6)
                                })
                                deleteRow();

                                const selects = document.querySelectorAll('.selected_role');
                                if (selects) {
                                    selects.forEach(select => {
                                        const user_id = select.closest('tr').querySelectorAll('td')[1].textContent;
                                        select.addEventListener('change', (e) => {
                                            const role = e.target.options[e.target.selectedIndex].textContent;
                                            fetch('/app/adminHandler.php', {
                                                method: "POST",
                                                headers: {
                                                    "Content-Type": "application/json"
                                                },
                                                body: JSON.stringify({
                                                    action: 'update_role',
                                                    table_name: "users_role",
                                                    user_id: user_id,
                                                    new_role: role
                                                })
                                            })
                                                .then(response => response.json())
                                                .then(data => data)
                                                .catch(error => console.log(error))
                                        })
                                    })
                                }

                            }
                            if (node.nodeType === Node.ELEMENT_NODE && node.classList.contains('admin__form')) {
                                const submit_button = node.querySelector('.insert_into_table');
                                submit_button.addEventListener('click', (e) => {
                                    requestData(e)
                                })
                            }
                        });
                    }
                });
            });

            observer.observe(admin_cont, {
                childList: true,
                subtree: true
            });
        } else {
            alert('Нет прав доступа')
        }
    }

    homeInit() {
        const title = document.querySelector('.home__title');
        const contentHeight = title.scrollHeight;

        title.style.height = contentHeight + 'px';

        const line_h = document.querySelector('.home__line--h');
        const line_v = document.querySelector('.home__line--v');

        line_h.style.height = '300px';
        line_v.style.height = '153px';

        const info = document.querySelector('.home__info');
        const infoHeight = info.scrollHeight;

        info.style.height = infoHeight + 'px';

    }
}