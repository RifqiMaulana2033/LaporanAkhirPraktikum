<?php
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class Auth extends ResourceController
{
    protected $format = 'json';

    // Buka gembok CORS
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }
    }

public function login()
    {
        try {
            // Tangkap data dengan cara yang lebih aman
            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');
            
            // Cek apakah data dikirim via JSON (Axios)
            $json = $this->request->getJSON();
            if ($json) {
                $username = $json->username ?? $username;
                $password = $json->password ?? $password;
            }

            if (empty($username) || empty($password)) {
                return $this->failUnauthorized('Username atau Password tidak boleh kosong.');
            }

            $model = new \App\Models\UserModel();
            
            $user = $model->where('username', $username)
                          ->orWhere('useremail', $username)
                          ->first();

            if ($user) {
                if ($password === $user['userpassword'] || password_verify($password, $user['userpassword'])) {
                    return $this->respond([
                        'status'   => 200,
                        'error'    => null,
                        'messages' => 'Login Berhasil',
                        'data'     => [
                            'id'       => $user['id'],
                            'username' => $user['username'],
                            'token'    => base64_encode("TOKEN-SECRET-" . $user['username'])
                        ]
                    ], 200);
                }
            }
            
            return $this->failUnauthorized('Username atau Password yang Anda masukkan salah.');

        } catch (\Throwable $e) {
            // Menangkap FATAL ERROR sekalipun
            return $this->respond([
                'status' => 500,
                'error' => 500,
                'messages' => 'Error Backend CI4: ' . $e->getMessage() . ' di baris ' . $e->getLine()
            ], 500);
        }
    }
}