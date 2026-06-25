<?php
namespace App\Controllers;
use App\Models\ArtikelModel;

class Artikel extends BaseController
{
    public function index()
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->findAll();
        return view('artikel/index', compact('artikel', 'title'));
    }

    public function view($slug)
    {
        $model = new ArtikelModel();
        // Ubah pencariannya jadi pake select dan join
        $artikel = $model->select('artikel.*, kategori.nama_kategori')
                        ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                        ->where(['slug' => $slug])
                        ->first();

        if (!$artikel) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $title = $artikel['judul'];
        return view('artikel/detail', compact('artikel', 'title'));
    }

    public function admin_index()
    {
        $q = $this->request->getVar('q') ?? '';
        $kat = $this->request->getVar('kategori') ?? ''; // Ambil filter kategori
        
        $model = new ArtikelModel();
        $katModel = new \App\Models\KategoriModel();

        // Logic Join & Filter
        $builder = $model->select('artikel.*, kategori.nama_kategori')
                        ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

        if ($q) $builder->like('judul', $q);
        if ($kat) $builder->where('artikel.id_kategori', $kat); // Filter berdasarkan kategori

        $data = [
            'title'         => 'Daftar Artikel',
            'q'             => $q,
            'kategori_list' => $katModel->findAll(), // Kirim data buat UI pilihan kategori
            'artikel'       => $builder->paginate(10),
            'pager'         => $model->pager,
        ];

        return view('artikel/admin_index', $data);
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        // Validasi ditambahin aturan buat gambar biar nggak error kalo user lupa upload
        $validation->setRules([
            'judul'       => 'required', 
            'id_kategori' => 'required',
        ]); 
        
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $file = $this->request->getFile('gambar');
            $namaFile = '';

            // Cek apakah ada file gambar yang diupload
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Pindahin file ke folder public/gambar dengan nama acak biar ngga bentrok
                $namaFile = $file->getRandomName();
                $file->move(ROOTPATH . 'public/gambar', $namaFile);
            }

            $artikel = new ArtikelModel();
            $artikel->insert([
                'judul'       => $this->request->getPost('judul'),
                'isi'         => $this->request->getPost('isi'),
                'slug'        => url_title($this->request->getPost('judul'), '-', true),
                'id_kategori' => $this->request->getPost('id_kategori'),
                'gambar'      => $namaFile // Simpan nama file ke database
            ]);
            return redirect()->to('admin/artikel');
        }

        $title = "Tambah Artikel";
        $kategoriModel = new \App\Models\KategoriModel();
        $kategori = $kategoriModel->findAll();

        return view('artikel/form_add', compact('title', 'kategori'));
    }

    public function edit($id)
    {
        $artikel = new ArtikelModel();
        $data_lama = $artikel->find($id); // Ambil data lama buat tau nama foto sebelumnya
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'id_kategori' => 'required'
        ]);
        
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $file = $this->request->getFile('gambar');
            
            // Cek apakah ada file baru yang diupload
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Kalau ada foto baru, hapus foto lama (opsional tapi bagus biar ga menuhin memori)
                if (!empty($data_lama['gambar']) && file_exists(ROOTPATH . 'public/gambar/' . $data_lama['gambar'])) {
                    unlink(ROOTPATH . 'public/gambar/' . $data_lama['gambar']);
                }
                
                // Upload foto baru
                $namaFile = $file->getRandomName();
                $file->move(ROOTPATH . 'public/gambar', $namaFile);
            } else {
                // Kalau ngga ada upload baru, tetep pake nama file yang lama
                $namaFile = $data_lama['gambar'];
            }

            $artikel->update($id, [
                'judul'       => $this->request->getPost('judul'),
                'isi'         => $this->request->getPost('isi'),
                'id_kategori' => $this->request->getPost('id_kategori'),
                'gambar'      => $namaFile
            ]);
            return redirect()->to('admin/artikel');
        }

        $data = $data_lama;
        $title = "Edit Artikel";
        $kategoriModel = new \App\Models\KategoriModel();
        $kategori = $kategoriModel->findAll();

        return view('artikel/form_edit', compact('title', 'data', 'kategori'));
    }

        public function delete($id)
    {
        $artikel = new ArtikelModel();
        $artikel->delete($id);
        return redirect()->to('admin/artikel');
    }
}