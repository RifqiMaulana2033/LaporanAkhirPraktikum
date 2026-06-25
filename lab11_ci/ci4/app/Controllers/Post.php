<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ArtikelModel;

class Post extends ResourceController
{
    use ResponseTrait;

    // 1. Menampilkan semua data (GET)
    public function index()
    {
        $model = new ArtikelModel();
        // Mengambil semua data artikel diurutkan dari yang terbaru
        $data['artikel'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }

    // 2. Menampilkan satu data spesifik berdasarkan ID (GET)
    public function show($id = null)
    {
        $model = new ArtikelModel();
        $data = $model->where('id', $id)->first();
        
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('Data tidak ditemukan.');
        }
    }

    // 3. Menambahkan data baru (POST)
    public function create()
    {
        $model = new ArtikelModel();
        $data = [
            'judul'       => $this->request->getVar('judul'),
            'isi'         => $this->request->getVar('isi'),
            // Kita set id_kategori default ke 1 kalo kosong, biar ga nabrak database lu
            'id_kategori' => $this->request->getVar('id_kategori') ?? 1 
        ];
        
        $model->insert($data);
        
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data artikel berhasil ditambahkan.'
            ]
        ];
        return $this->respondCreated($response);
    }

    // 4. Mengubah data (PUT/PATCH)
    public function update($id = null)
    {
        $model = new ArtikelModel();
        $data = [
            'judul'       => $this->request->getVar('judul'),
            'isi'         => $this->request->getVar('isi'),
            'id_kategori' => $this->request->getVar('id_kategori') ?? 1
        ];
        
        $model->update($id, $data);
        
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data artikel berhasil diubah.'
            ]
        ];
        return $this->respond($response);
    }

    // 5. Menghapus data (DELETE)
    public function delete($id = null)
    {
        $model = new ArtikelModel();
        $data = $model->where('id', $id)->first();
        
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data artikel berhasil dihapus.'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan.');
        }
    }

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        
        // Tangkap preflight request dari browser
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }
}