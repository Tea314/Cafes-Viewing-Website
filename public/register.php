<?php
require __DIR__.'/libs/bootstrap.php';

// Redirect logged-in users
if (is_user_logged_in()) {
    redirect_to('index.php');
}

// Function to create admin account if it doesn't exist
function create_admin_account($pdo)
{
    $admin_email = 'admin@gmail.com';
    $admin_username = 'admin';
    $admin_password = 'Admin1234#';

    try {
        // Check if admin account already exists
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email OR username = :username');
        $stmt->execute([':email' => $admin_email, ':username' => $admin_username]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // Create admin account
            if (register_user($admin_email, $admin_username, $admin_password, 'admin')) {
                // Optionally log success
                error_log('Admin account created successfully.');
            } else {
                error_log('Failed to create admin account.');
            }
        }
    } catch (PDOException $e) {
        error_log('Error checking/creating admin account: '.$e->getMessage());
    }
}

// Execute admin account creation on first GET request
if (is_get_request()) {
    $pdo = Database::getConnection(); // Assuming Database class is defined in bootstrap.php
    create_admin_account($pdo);
}

$errors = [];
$inputs = [];

if (is_post_request()) {
    $fields = [
        'username' => 'string | required | alphanumeric | between: 3, 25 | unique: users, username',
        'email' => 'email | required | email | unique: users, email',
        'password' => 'string | required | secure',
        'password2' => 'string | required | same: password',
        'agree' => 'string | required',
    ];
    $messages = [
        'password2' => [
            'required' => 'Please enter the password again',
            'same' => 'The password does not match',
        ],
        'agree' => [
            'required' => 'You need to agree to the term of services to register',
        ],
    ];

    [$inputs, $errors] = filter($_POST, $fields, $messages);

    if ($errors) {
        redirect_with('register.php', [
            'inputs' => $inputs,
            'errors' => $errors,
        ]);
    }

    if (register_user($inputs['email'], $inputs['username'], $inputs['password'])) {
        redirect_with_message(
            'login.php',
            'Your account has been created successfully. Please login here.'
        );
    }
} elseif (is_get_request()) {
    [$inputs, $errors] = session_flash('inputs', 'errors');
}

?>

<?php viewName('header', ['title' => 'Register']) ?>


<body>
<main>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg rounded-4" style="width: 100%; max-width: 500px;">
            <h2 class="text-center mb-4">Sign Up</h2>
            <form action="register.php" method="post">
                <div class="mb-2">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($inputs['username'] ?? '') ?>"
                           class="form-control <?= error_class($errors, 'username') ?>" placeholder="Enter 3-25 characters">
                    <small class="text-danger"><?= htmlspecialchars($errors['username'] ?? '') ?></small>
                </div>

                <div class="mb-2">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($inputs['email'] ?? '') ?>"
                           class="form-control <?= error_class($errors, 'email') ?>" placeholder="Enter your email">
                    <small class="text-danger"><?= htmlspecialchars($errors['email'] ?? '') ?></small>
                </div>

                <div class="mb-2 position-relative">
                    <label for="password" class="form-label">Password:</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                               value="<?= htmlspecialchars($inputs['password'] ?? '') ?>"
                               class="form-control <?= error_class($errors, 'password') ? 'is-invalid text-black' : '' ?>"
                               placeholder="Enter 8-64 characters"
                               title="Password must be 8-64 characters, with at least one number, one uppercase letter, one lowercase letter, and one special character"
                               aria-describedby="passwordFeedback">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye" id="passwordEye"></i>
                        </button>
                    </div>
                    <small class="text-danger"><?= htmlspecialchars($errors['password'] ?? '') ?></small>
                </div>
                <div class="mb-2">
                    <p class="m-0">• Password must be 8-64 characters long.</p>
                    <p class="m-0">• Password must contain at least one number, one uppercase letter, one lowercase letter, and one special character.</p>
                </div>

                <div class="mb-2 position-relative">
                    <label for="password2" class="form-label">Password Again:</label>
                    <div class="input-group">
                        <input type="password" name="password2" id="password2"
                               value="<?= htmlspecialchars($inputs['password2'] ?? '') ?>"
                               class="form-control <?= error_class($errors, 'password2') ? 'is-invalid text-black' : '' ?>"
                               placeholder="Confirm your password"
                               title="Must match the password above"
                               aria-describedby="password2Feedback">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                            <i class="bi bi-eye" id="password2Eye"></i>
                        </button>
                    </div>
                    <div id="password2Feedback" class="invalid-feedback d-flex align-items-center" style="<?php
                        echo error_class($errors, 'password2') ? '' : 'display: none';
?>">
                        <?= htmlspecialchars($errors['password2'] ?? '') ?>
                    </div>
                </div>

                <div class="mb-2">
                    <label for="agree" class="form-check-label">
                        <input type="checkbox" name="agree" id="agree" value="checked"
                               class="form-check-input <?= error_class($errors, 'agree') ?>"
                               <?= isset($inputs['agree']) ? 'checked' : '' ?> /> I agree with the
                        <a href="#" title="term of services">term of services</a>
                    </label>
                    <small class="text-danger"><?= htmlspecialchars($errors['agree'] ?? '') ?></small>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary w-50" type="submit">Register</button>
                </div>
                <div class="text-center mt-2 fs-6">
                    Already a member? <a href="login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>
</main>
</body>
<script src="<?= BASE_URL ?>/js/register.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

