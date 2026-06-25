<?= $this->include('template/admin_header'); ?>

<form method="get" class="form-search">
    <input type="text" name="q" value="<?= $q; ?>" placeholder="Cari judul...">
    <select name="kategori" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        <?php foreach($kategori_list as $k): ?>
            <option value="<?= $k['id_kategori']; ?>" <?= (request()->getVar('kategori') == $k['id_kategori']) ? 'selected' : ''; ?>>
                <?= $k['nama_kategori']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Cari" class="btn btn-primary">
</form>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Kategori</th>
            <th>Judul & Preview Isi</th>
            <th width="150">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if($artikel): foreach($artikel as $row): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><span class="badge-kat"><?= $row['nama_kategori'] ?? 'Umum'; ?></span></td>
            <td>
                <strong><?= $row['judul']; ?></strong>
                
                <?php if(!empty($row['gambar'])): ?>
                    <div style="margin-top: 10px;">
                        <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="thumbnail" style="height: 60px; border-radius: 4px; border: 1px solid #ccc; object-fit: cover;">
                    </div>
                <?php endif; ?>
                <p style="color: #777; font-size: 12px; margin-top: 5px;">
                    <?= substr($row['isi'], 0, 80); ?>...
                </p>
            </td>
            <td>
                <a class="btn" href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>">Ubah</a>
                <a class="btn btn-danger" onclick="return confirm('Yakin hapus?');" href="<?= base_url('/admin/artikel/delete/' . $row['id']);?>">Hapus</a>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="4" style="text-align:center;">Data tidak ditemukan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $pager->only(['q', 'kategori'])->links(); ?>
<?= $this->include('template/admin_footer'); ?>