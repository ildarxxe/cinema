import AuthValid from "./AuthValid.js";
import RegValid from "./RegValid.js";

export default class Init {
    countSeats;
    constructor(userHandler) {
        this.userHandler = userHandler;
        this.countSeats = 36;
    }

    cinemaInit() {
        const cinema_content = document.querySelector('.cinema__content');
        for (let i = 1; i <= this.countSeats; i++) {
            const cell = document.createElement('div');
            cell.classList.add('cell');

            cell.setAttribute("data-set", `${i}`);
            cell.innerHTML = i.toString();
            cinema_content.appendChild(cell);
        }
        const cells = document.querySelectorAll('.cell');
        cells.forEach(cell => {
            cell.addEventListener('click', () => {
                console.log(cell.dataset.set)
            })
        })
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