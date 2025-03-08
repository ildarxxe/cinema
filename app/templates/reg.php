<form id="reg_form" class="auth-form mt-5">
    <div class="form__inner mx-auto vstack">
        <h1 class="form__title text-center">Регистрация</h1>
        <p id="reg__error" class="header__other"></p>
        <label class="mb-3 form__label">
            Имя:
            <input type="text" id="reg_name" required name="name" class="form-control">
            <span class="form__error"></span>
        </label>
        <label class="mb-3 form__label">
            Email:
            <input type="email" id="reg_email" required name="email" class="form-control">
            <span class="form__error"></span>
        </label>
        <div class="phones">
            <label class="form__label mb-3">
                Номер телефона:
                <input type="text" class="form-control input__phone" name="phone1" id="phone1">
                <span class="form__error"></span>
            </label>
        </div>
        <button type="button" class="add_phone">+</button>
        <label class="mb-3 form__label">
            Пароль:
            <input type="password" id="reg_password" required name="password" class="form-control">
            <span class="form__error"></span>
        </label>
        <label class="mb-3 form__label">
            Подтвердите пароль:
            <input type="password" required id="password_confirm" name="password_confirm" class="form-control">
            <span class="form__error"></span>
        </label>
        <input type="button" disabled id="reg_submit" value="Зарегистрироваться" class="form__submit btn btn-primary">
    </div>
</form>