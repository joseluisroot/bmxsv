<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function loginPost()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('email', $email)->first();

        if ($usuario && password_verify($password, $usuario['password'])) {
            session()->set('usuario', [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email']
            ]);
            return redirect()->to('/dashboard'); // puedes cambiarlo después
        }

        return redirect()->back()->with('error', 'Credenciales inválidas');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('mensaje', 'Sesión cerrada correctamente.');
    }

}