<?php

require __DIR__.'/libs/bootstrap.php';
if (is_user_logged_in()) {
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
        redirect_to('admin.php');
    }
    redirect_to('index.php');
}
$inputs = [];
$errors = [];

if (is_post_request()) {

    [$inputs, $errors] = filter($_POST, [
        'username' => 'string | required',
        'password' => 'string | required',
    ]);

    if ($errors) {
        redirect_with('login.php', ['errors' => $errors, 'inputs' => $inputs]);
    }

    // if login fails
    if (! login($inputs['username'], $inputs['password'])) {

        $errors['login'] = 'Invalid username or password';

        redirect_with('login.php', [
            'errors' => $errors,
            'inputs' => $inputs,
        ]);
    }
    // login successfully
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
        redirect_to('admin.php');
    } else {
        redirect_to('index.php');
    }

} elseif (is_get_request()) {
    [$errors, $inputs] = session_flash('errors', 'inputs');
}
?>
<?php viewName('header', ['title' => 'Login']) ?>
<?php if (isset($errors['login'])) { ?>
    <div class="alert alert-error">
        <?= $errors['login'] ?>
    </div>
<?php } ?>
<main>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg rounded-4" style="width: 100%; max-width: 500px;">
            <h2 class="text-center mb-3">Login</h2>
            <form action="login.php" method="post">
            <div class="mb-2">
                <label for="username">Username:</label>
                <input class="form-control" type="text" name="username" id="username"    >
            </div>

            <div class="mb-3">
                <label for="password">Password:</label>
                <input class="form-control" type="password" name="password" id="password">
            </div>

            <section>
                <button class="btn btn-primary me-3 w-25" type="submit">Login</button>
                <a href="register.php">Register</a>
            </section>
        </form>
        </div>
    </div>

</main>
