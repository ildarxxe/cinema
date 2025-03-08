<form id="auth_form" class="auth-form mt-5">
    <div class="form__inner mx-auto vstack">
        <h1 class="form__title text-center">Авторизация</h1>

        <p id="auth__error" class="header__other"></p>

        <label class="mb-3 form__label">
            Email:
            <input type="email" id="auth_email" name="email" class="form-control">
            <span class="form__error"></span>
        </label>
        <label class="mb-3 form__label">
            Пароль:
            <input type="password" id="auth_password" name="password" class="form-control">
            <span class="form__error"></span>
        </label>
        <input type="button" disabled id="auth_submit" value="Войти" class="btn btn-primary form__submit">
    </div>
</form>