<?php
namespace App\Cells;
use App\Models\ArtikelModel;

class ArtikelTerkini
{
    public function render()
    {
        $model = new ArtikelModel();
        // Mengambil 5 artikel terbaru berdasarkan tanggal
        $artikel = $model->orderBy('created_at', 'DESC')->limit(5)->findAll();
        
        return view('components/artikel_terkini', ['artikel' => $artikel]);
    }
}