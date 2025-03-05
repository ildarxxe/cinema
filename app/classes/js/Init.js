import AuthValid from "./AuthValid.js";
import RegValid from "./RegValid.js";

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
        new RegValid();
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
            const change_name = document.getElementById('change_name').value;
            const change_email = document.getElementById('change_email').value;

            fetch('./app/formHandler.php', {
                method: "PUT",
                header: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    action: "put_profile",
                    change_name,
                    change_email
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
        })

        const change_password_btn = document.querySelector('.change_password_submit');
        change_password_btn.addEventListener('click', (e) => {
            e.preventDefault();
            const current_password = document.getElementById('current_password').value;
            const change_password = document.getElementById('change_password').value;

            fetch('./app/formHandler.php', {
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
                deleteProfile();
            }
        })

        function deleteProfile() {
            delete_profile_submit.addEventListener('click', (e) => {
                e.preventDefault();

                const delete_password = document.getElementById('delete_password').value;
                fetch('./app/formHandler.php', {
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
            })
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