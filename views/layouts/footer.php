</div>
<footer class="text-center text-muted py-3 mt-4 border-top" style="font-size: 0.85rem;">
    <strong>Lutfi POS</strong> v1.0.0 &mdash; Point of Sale System &copy; <?= date('Y') ?> Lutfi Febrianto
</footer>

<!-- Modal Cetak Global -->
<div class="modal fade" id="modalCetak" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title"><i class="bi bi-printer"></i> Cetak</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="cetakFrame" style="width:100%;height:500px;border:none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-sm" id="btnCetakPrint">
                    <i class="bi bi-printer"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Global -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Konfirmasi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="konfirmasiPesan"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="konfirmasiOk">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Alert Global -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="toastAlert" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastPesan"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="js/app.js"></script>
<?php if (($page ?? '') === 'kasir'): ?>
<script src="js/kasir.js"></script>
<?php endif; ?>
<?php if (($page ?? '') === 'barcode'): ?>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script src="js/barcode.js"></script>
<?php endif; ?>
<script>
// Lutfi POS branding protection
(function(){
    var f = document.querySelector('footer');
    if (!f || f.innerHTML.indexOf('Lutfi POS') === -1) {
        document.body.innerHTML = '<div style="text-align:center;padding:50px;font-family:sans-serif;">'
            + '<h1 style="color:red;">Aplikasi Tidak Sah</h1>'
            + '<p>Branding <strong>Lutfi POS</strong> telah dihapus atau dimodifikasi.</p>'
            + '<p>Aplikasi tidak dapat dijalankan.</p></div>';
    }
})();
</script>
</body>
</html>
