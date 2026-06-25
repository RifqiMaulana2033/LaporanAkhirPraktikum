<?= $this->include('template/header'); ?>

<article class="entry">
    <h2 style="margin-bottom: 10px;"><?= $artikel['judul']; ?></h2>
    
    <p style="margin-bottom: 20px;">
        <span style="background-color: #e1f5fe; color: #0288d1; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: bold;">
            Kategori: <?= $artikel['nama_kategori'] ?? 'Umum'; ?>
        </span>
    </p>
    
    <?php if(!empty($artikel['gambar'])): ?>
        <img src="<?= base_url('/gambar/' . $artikel['gambar']);?>" alt="<?= $artikel['judul']; ?>" style="max-width: 100%; height: auto; margin-bottom: 15px; border-radius: 8px;">
    <?php endif; ?>
    
    <div style="text-align: justify; font-size: 15px; color: #444;">
        <?= nl2br($artikel['isi']); ?>
    </div>
</article>

<?= $this->include('template/footer'); ?>