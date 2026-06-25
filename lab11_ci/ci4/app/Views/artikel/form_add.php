<?= $this->include('template/admin_header'); ?>

<h2>Tambah Artikel</h2>
<form action="" method="post" enctype="multipart/form-data">
    <p>
        <label>Judul Artikel</label>
        <input type="text" name="judul" placeholder="Masukkan Judul" required>
    </p>
    <p>
        <label>Kategori</label>
        <select name="id_kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Upload Gambar Thumbnail</label>
        <input type="file" name="gambar" class="form-control" accept="image/*" style="padding: 8px; cursor: pointer;">
    </p>
    <p>
        <label>Isi Artikel</label>
        <textarea name="isi" cols="50" rows="10" placeholder="Tulis isi artikel di sini..." required></textarea>
    </p>
    <p><input type="submit" value="Kirim" class="btn btn-primary"></p>
</form>

<?= $this->include('template/admin_footer'); ?>