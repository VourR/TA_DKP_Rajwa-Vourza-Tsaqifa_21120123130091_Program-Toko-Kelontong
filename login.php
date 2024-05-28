<?php
session_start();

class User {
    private $email;
    private $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }
}

class Auth {
    private $users;

    public function __construct() {
        if (!isset($_SESSION['users'])) {
            $_SESSION['users'] = [];
        }
        $this->users = &$_SESSION['users'];
    }

    public function login($email, $password) {
        foreach ($this->users as $user) {
            if ($email === $user->getEmail() && $password === $user->getPassword()) {
                $_SESSION['logged_in'] = true;
                header('Location: index.php');
                exit;
            }
        }
        return '<div class="alert alert-danger">Email atau Password salah!</div>';
    }

    public function register($email, $password) {
        foreach ($this->users as $user) {
            if ($email === $user->getEmail()) {
                return '<div class="alert alert-danger">Email sudah terdaftar!</div>';
            }
        }
        // Membuat instance User untuk pengguna yang akan diregistrasi
        $newUser = new User($email, $password);
        // Menyimpan informasi pengguna baru ke dalam session
        $this->users[] = $newUser;
        return '<div class="alert alert-success">Akun berhasil terdaftar!</div>';
    }
}

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (isset($_POST['login']) && $_POST['login'] === 'Login') {
        $error_message = $auth->login($email, $password);
    } elseif (isset($_POST['register']) && $_POST['register'] === 'Register') {
        $success_message = $auth->register($email, $password);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card mb-3 bg-subtle" style="border-radius: 1rem;">
            <div class="row g-0">
                <div class="col-md-7">
                    <img src="images/market.jpg" class="img-fluid rounded-start" alt="Gambar">
                </div>
                <div class="col-md-5">
                    <div class="card-body mt-3 mb-3">
                        <h1 class="card-title text-center">Login/Register</h1>
                        <?php if (isset($error_message)): ?>
                                <?php echo $error_message; ?>
                        <?php endif; ?>
                        <?php if (isset($success_message)): ?>
                                <?php echo $success_message; ?>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="form-group text-start">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group text-start">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="buttons text-center" style="margin-top: 5px;">
                                <button type="submit" name="login" value="Login" class="btn btn-primary">Login</button>
                                <button type="submit" name="register" value="Register" class="btn btn-secondary">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

