<?= $this->include('template/admin_header'); ?>

<h2>Edit Artikel</h2>
<form action="" method="post" enctype="multipart/form-data">
    <p>
        <label>Judul Artikel</label>
        <input type="text" name="judul" value="<?= $data['judul']; ?>" required>
    </p>
    
    <p>
        <label>Kategori</label>
        <select name="id_kategori" required>
            <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id_kategori']; ?>" <?= ($data['id_kategori'] == $k['id_kategori']) ? 'selected' : ''; ?>>
                    <?= $k['nama_kategori']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
        <label>Ganti Gambar</label>
        <?php if(!empty($data['gambar'])): ?>
            <div style="margin-bottom: 10px;">
                <small>Gambar saat ini:</small><br>
                <img src="<?= base_url('/gambar/' . $data['gambar']); ?>" width="150" style="border-radius: 4px; border: 1px solid #ccc;">
            </div>
        <?php endif; ?>
        <input type="file" name="gambar" class="form-control" accept="image/*" style="padding: 8px; cursor: pointer;">
        <br>
        <small style="color: #5b9bd5;">*Kosongkan jika tidak ingin mengganti gambar</small>
    </p>
    <p>
        <label>Isi Artikel</label>
        <textarea name="isi" cols="50" rows="10" required><?= $data['isi']; ?></textarea>
    </p>
    
    <p><input type="submit" value="Update" class="btn btn-primary"></p>
</form>

<?= $this->include('template/admin_footer'); ?>