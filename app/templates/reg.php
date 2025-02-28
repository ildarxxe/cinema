<form id="reg_form" class="auth-form mt-5">
    <div class="form__inner mx-auto vstack">
        <h1 class="form__title text-center">Registration</h1>
        <label class="mb-3">
            Name:
            <input type="text" id="name" required name="name" class="form-control">
        </label>
        <label class="mb-3">
            Email:
            <input type="email" id="email" required name="email" class="form-control">
        </label>
        <label class="mb-3">
            Password:
            <input type="password" id="password" required name="password" class="form-control">
        </label>
        <label class="mb-3">
            Confirm password:
            <input type="password" required name="password_confirm" class="form-control">
        </label>
        <input type="button" id="reg_submit" value="Submit" class="btn btn-primary">
    </div>
</form>