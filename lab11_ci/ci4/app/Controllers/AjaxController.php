<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use App\Models\ArtikelModel;

class AjaxController extends Controller
{
    // 1. Fungsi buat nampilin halaman webnya
    public function index()
    {
        $data['title'] = 'Data Artikel (AJAX)';
        return view('ajax/index', $data);
    }

    public function getData()
    {
        $model = new ArtikelModel();
        $data = $model->findAll();
        
        // Kirim data dalam format JSON
        return $this->response->setJSON($data);
    }

    // 3. Fungsi buat ngehapus data via AJAX
    public function delete($id)
    {
        $model = new ArtikelModel();
        $model->delete($id);
        
        $data = [
            'status' => 'OK',
            'pesan' => 'Artikel berhasil dihapus'
        ];
        
        // Kirim konfirmasi JSON
        return $this->response->setJSON($data);
    }
}