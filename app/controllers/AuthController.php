<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;
use App\Requests\LoginRequest;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
        }
        $this->view('auth/login', ['pageTitle' => 'Đăng nhập']);
        unset($_SESSION['_old']);
    }

    public function login(): void
    {
        $errors = LoginRequest::validate($_POST);
        if ($errors) {
            $_SESSION['_old'] = ['email' => $_POST['email'] ?? ''];
            flash('error', reset($errors));
            $this->redirect('/dang-nhap');
        }

        if (!Auth::attempt($_POST['email'], $_POST['password'])) {
            $_SESSION['_old'] = ['email' => $_POST['email'] ?? ''];
            flash('error', 'Email hoặc mật khẩu không đúng.');
            $this->redirect('/dang-nhap');
        }

        unset($_SESSION['_old']);
        flash('success', 'Đăng nhập thành công.');
        $this->redirect(Auth::isAdmin() ? '/admin' : '/');
    }

    public function registerForm(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
        }
        $this->view('auth/register', ['pageTitle' => 'Đăng ký']);
        unset($_SESSION['_old']);
    }

    public function register(): void
    {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        $userModel = new User();
        $errors = [];

        if ($name === '') {
            $errors[] = 'Vui lòng nhập họ và tên.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ.';
        } elseif ($userModel->emailExists($email)) {
            $errors[] = 'Email này đã được đăng ký.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải có tối thiểu 6 ký tự.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Mật khẩu nhập lại không khớp.';
        }

        if ($errors) {
            $_SESSION['_old'] = ['name' => $name, 'email' => $email, 'phone' => $phone];
            flash('error', reset($errors));
            $this->redirect('/dang-ky');
        }

        $userId = $userModel->register([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => $password,
        ]);

        Auth::login($userId);
        unset($_SESSION['_old']);
        flash('success', 'Đăng ký thành công. Chào mừng ' . $name . '!');
        $this->redirect('/');
    }

    public function logout(): void
    {
        Auth::logout();
        flash('success', 'Bạn đã đăng xuất.');
        $this->redirect('/');
    }
}
