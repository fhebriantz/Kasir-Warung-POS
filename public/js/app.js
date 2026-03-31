// ===================== GLOBAL CONFIRM & ALERT =====================
// Pengganti confirm() dan alert() bawaan browser
// karena tidak berfungsi di PHP Desktop (Chromium embedded)

function appConfirm(pesan, callback) {
    var $modal = $('#modalKonfirmasi');
    $('#konfirmasiPesan').html(pesan.replace(/\n/g, '<br>'));
    var bsModal = bootstrap.Modal.getOrCreateInstance($modal[0]);
    // Hapus event lama
    $('#konfirmasiOk').off('click').on('click', function () {
        bsModal.hide();
        if (callback) callback();
    });
    bsModal.show();
}

function appAlert(pesan, tipe) {
    tipe = tipe || 'info';
    var bgMap = { success: 'bg-success', danger: 'bg-danger', warning: 'bg-warning text-dark', info: 'bg-info' };
    var $toast = $('#toastAlert');
    $toast.removeClass('bg-success bg-danger bg-warning bg-info text-dark text-white')
          .addClass((bgMap[tipe] || 'bg-info') + ' text-white');
    if (tipe === 'warning') $toast.removeClass('text-white');
    $('#toastPesan').html(pesan.replace(/\n/g, '<br>'));
    var toast = bootstrap.Toast.getOrCreateInstance($toast[0], { delay: 3000 });
    toast.show();
}

// ===================== GLOBAL PRINT =====================
// Pengganti window.open() + window.print() untuk PHP Desktop
function appPrint(url, callback) {
    var $modal = $('#modalCetak');
    var $frame = $('#cetakFrame');
    $frame.attr('src', url);
    var bsModal = bootstrap.Modal.getOrCreateInstance($modal[0]);

    $('#btnCetakPrint').off('click').on('click', function () {
        var frame = $frame[0];
        try {
            frame.contentWindow.focus();
            frame.contentWindow.print();
        } catch (e) {
            // Fallback: print seluruh halaman
            window.print();
        }
    });

    $modal.off('hidden.bs.modal').on('hidden.bs.modal', function () {
        $frame.attr('src', 'about:blank');
        if (callback) callback();
    });

    bsModal.show();
}

// Print halaman saat ini (untuk laporan)
function appPrintPage() {
    window.print();
}

// ===================== FORM CONFIRM via data-confirm =====================
// Otomatis tangkap semua form dengan atribut data-confirm
$(document).ready(function () {
    $(document).on('submit', 'form[data-confirm]', function (e) {
        var $form = $(this);
        if ($form.data('confirmed')) {
            $form.removeData('confirmed');
            return true;
        }
        e.preventDefault();
        appConfirm($form.data('confirm'), function () {
            $form.data('confirmed', true);
            $form[0].submit();
        });
        return false;
    });
});
