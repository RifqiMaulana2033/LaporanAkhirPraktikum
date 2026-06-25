<?= $this->include('template/admin_header'); ?>

<h1>Data Artikel (Mode AJAX)</h1>
<table class="table" id="artikelTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Kategori</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    
    // Fungsi untuk nampilin pesan loading [cite: 954]
    function showLoadingMessage() {
        $('#artikelTable tbody').html('<tr><td colspan="5" style="text-align:center;">Loading data...</td></tr>');
    }

    // Fungsi utama buat ngambil data dari server [cite: 963]
    function loadData() {
        showLoadingMessage(); 
        
        // Request AJAX GET ke controller [cite: 966]
        $.ajax({
            url: "<?= base_url('ajax/getData') ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {
                var tableBody = "";
                
                // Looping data JSON buat dibikin baris tabel [cite: 976, 977]
                if(data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        var row = data[i];
                        var kategori = row.nama_kategori ? row.nama_kategori : 'Umum';
                        
                        tableBody += '<tr>';
                        tableBody += '<td>' + row.id + '</td>';
                        tableBody += '<td><span class="badge-kat">' + kategori + '</span></td>';
                        tableBody += '<td><b>' + row.judul + '</b></td>';
                        tableBody += '<td>' + row.status + '</td>';
                        tableBody += '<td>';
                        // Tombol edit ngarah ke form biasa, tombol hapus dikasih class khusus btn-delete [cite: 984, 986]
                        tableBody += '<a href="<?= base_url('admin/artikel/edit/') ?>' + row.id + '" class="btn" style="margin-right:5px;">Edit</a>';
                        tableBody += '<a href="#" class="btn btn-danger btn-delete" data-id="' + row.id + '">Hapus AJAX</a>';
                        tableBody += '</td>';
                        tableBody += '</tr>';
                    }
                } else {
                    tableBody = '<tr><td colspan="5" style="text-align:center;">Tidak ada data.</td></tr>';
                }
                
                // Tembak HTML yang udah dirakit ke dalam tbody [cite: 993]
                $('#artikelTable tbody').html(tableBody);
            },
            error: function() {
                $('#artikelTable tbody').html('<tr><td colspan="5" style="text-align:center; color:red;">Gagal memuat data!</td></tr>');
            }
        });
    }

    // Panggil fungsi loadData pertama kali halaman dibuka [cite: 995]
    loadData();

    // Aksi ketika tombol hapus diklik [cite: 997]
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        // Konfirmasi sebelum hapus [cite: 1002]
        if (confirm('Apakah Anda yakin ingin menghapus artikel ID ' + id + ' ini?')) {
            $.ajax({
                url: "<?= base_url('ajax/delete/') ?>" + id,
                method: "DELETE", // Pakai method DELETE sesuai route [cite: 1007]
                success: function(response) {
                    alert(response.pesan);
                    loadData(); // Langsung reload tabel tanpa refresh halaman [cite: 1009]
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting article: ' + textStatus);
                }
            });
        }
    });

});
</script>

<?= $this->include('template/admin_footer'); ?>