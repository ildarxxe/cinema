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
}