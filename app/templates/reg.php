<form id="reg_form" class="auth-form mt-5">
    <div class="form__inner mx-auto vstack">
        <h1 class="form__title text-center">Registration</h1>
        <p id="reg__error" class="header__other"></p>
        <label class="mb-3 form__label">
            Name:
            <input type="text" id="reg_name" required name="name" class="form-control">
            <span class="form__error"></span>
        </label>
        <label class="mb-3 form__label">
            Email:
            <input type="email" id="reg_email" required name="email" class="form-control">
            <span class="form__error"></span>
        </label>
        <label class="mb-3 form__label">
            Password:
            <input type="password" id="reg_password" required name="password" class="form-control">
            <span class="form__error"></span>
        </label>
        <label class="mb-3 form__label">
            Confirm password:
            <input type="password" required id="password_confirm" name="password_confirm" class="form-control">
            <span class="form__error"></span>
        </label>
        <input type="button" disabled id="reg_submit" value="Submit" class="btn btn-primary">
    </div>
</form>