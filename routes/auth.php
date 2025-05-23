<?php

function register_user(string $email, string $username, string $password, bool $is_admin = false): bool
{
    $sql = 'INSERT INTO users(username, email, password, is_admin)
            VALUES(:username, :email, :password, :is_admin)';
    require_once __DIR__.'/../config/database.php';
    $conn = Database::getConnection();
    $statement = $conn->prepare($sql);

    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
    $statement->bindValue(':is_admin', (int) $is_admin, PDO::PARAM_INT);

    return $statement->execute();
}
function find_user_by_username(string $username)
{
    $sql = 'SELECT id, username, password, is_admin
            FROM users
            WHERE username=:username';

    $statement = Database::getConnection()->prepare($sql);
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}
function login(string $username, string $password): bool
{
    $user = find_user_by_username($username);

    // if user found, check the password
    if ($user && password_verify($password, $user['password'])) {

        // prevent session fixation attack
        session_regenerate_id();

        // set username in the session
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = (bool) $user['is_admin'];

        return true;
    }

    return false;
}
function is_user_logged_in(): bool
{
    return isset($_SESSION['username']);
}
function require_login(): void
{
    if (! is_user_logged_in()) {
        redirect_to('login.php');
    }
}
function logout(): void
{
    if (is_user_logged_in()) {
        unset($_SESSION['username'], $_SESSION['user_id'], $_SESSION['is_admin']);
        session_destroy();
        redirect_to('login.php');
    }
}
function current_user()
{
    if (is_user_logged_in()) {
        return $_SESSION['username'];
    }

    return 'guest';
}
