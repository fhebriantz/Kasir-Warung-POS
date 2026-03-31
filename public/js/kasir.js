$(document).ready(function () {
    let keranjang = [];

    // ===================== FORMAT HELPER =====================
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // ===================== SELECT2 INIT =====================
    $('#cariBarang').select2({
        placeholder: 'Ketik nama barang atau scan barcode...',
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: 'api.php?action=search_barang',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            },
            cache: true
        },
        templateResult: function (item) {
            if (!item.id) return item.text;
            return $('<div>')
                .append('<strong>' + item.nama + '</strong>')
                .append('<br><small class="text-muted">Rp ' + formatRupiah(item.harga_jual) + ' &middot; Stok: ' + item.stok + ' ' + item.satuan + '</small>');
        },
        templateSelection: function (item) {
            return item.nama || item.text;
        }
    });

    // ===================== AUTO-FOCUS PENCARIAN =====================
    function fokusKeSearch() {
        $('#cariBarang').select2('open');
    }
    setTimeout(fokusKeSearch, 300);

    // ===================== PILIH BARANG =====================
    $('#cariBarang').on('select2:select', function (e) {
        let data = e.params.data;
        tambahKeKeranjang(data);
        $(this).val(null).trigger('change');
        setTimeout(fokusKeSearch, 150);
    });

    function tambahKeKeranjang(item) {
        // Cek apakah sudah ada di keranjang
        let existing = keranjang.find(k => k.id == item.id);
        if (existing) {
            if (existing.jumlah + 1 > item.stok) {
                alert('Stok "' + item.nama + '" tidak mencukupi! (sisa: ' + item.stok + ')');
                return;
            }
            existing.jumlah += 1;
        } else {
            if (item.stok <= 0) {
                alert('Stok "' + item.nama + '" habis!');
                return;
            }
            keranjang.push({
                id: item.id,
                nama: item.nama,
                harga_jual: parseFloat(item.harga_jual),
                stok: parseInt(item.stok),
                satuan: item.satuan,
                jumlah: 1
            });
        }
        renderKeranjang();
    }

    // ===================== RENDER KERANJANG =====================
    function renderKeranjang() {
        let $body = $('#bodyKeranjang');
        $body.empty();

        if (keranjang.length === 0) {
            $body.html(
                '<tr id="rowKosong"><td colspan="6" class="text-center text-muted py-4">' +
                '<i class="bi bi-cart-x" style="font-size: 2rem;"></i>' +
                '<p class="mb-0 mt-1">Keranjang masih kosong</p></td></tr>'
            );
            $('#badgeTotal').text('0 item');
            hitungTotal();
            return;
        }

        keranjang.forEach(function (item, index) {
            let subtotal = item.harga_jual * item.jumlah;
            let row = '<tr>' +
                '<td class="text-muted">' + (index + 1) + '</td>' +
                '<td>' +
                    '<strong>' + escapeHtml(item.nama) + '</strong>' +
                    '<br><small class="text-muted">' + item.satuan + '</small>' +
                '</td>' +
                '<td class="text-end">Rp ' + formatRupiah(item.harga_jual) + '</td>' +
                '<td class="text-center">' +
                    '<div class="input-group input-group-sm justify-content-center">' +
                        '<button class="btn btn-outline-secondary btn-qty-minus" data-index="' + index + '" type="button">-</button>' +
                        '<input type="number" class="form-control text-center input-qty" ' +
                            'value="' + item.jumlah + '" min="1" max="' + item.stok + '" ' +
                            'data-index="' + index + '" style="max-width: 55px;">' +
                        '<button class="btn btn-outline-secondary btn-qty-plus" data-index="' + index + '" type="button">+</button>' +
                    '</div>' +
                '</td>' +
                '<td class="text-end fw-bold">Rp ' + formatRupiah(subtotal) + '</td>' +
                '<td class="text-center">' +
                    '<button class="btn btn-sm btn-outline-danger btn-hapus" data-index="' + index + '" title="Hapus">' +
                        '<i class="bi bi-trash"></i>' +
                    '</button>' +
                '</td>' +
                '</tr>';
            $body.append(row);
        });

        let totalItem = keranjang.reduce(function (sum, i) { return sum + i.jumlah; }, 0);
        $('#badgeTotal').text(totalItem + ' item');

        hitungTotal();
    }

    // ===================== QTY HANDLERS =====================
    $(document).on('click', '.btn-qty-minus', function () {
        let idx = $(this).data('index');
        if (keranjang[idx].jumlah > 1) {
            keranjang[idx].jumlah -= 1;
            renderKeranjang();
        }
    });

    $(document).on('click', '.btn-qty-plus', function () {
        let idx = $(this).data('index');
        if (keranjang[idx].jumlah < keranjang[idx].stok) {
            keranjang[idx].jumlah += 1;
            renderKeranjang();
        } else {
            alert('Stok maksimal: ' + keranjang[idx].stok);
        }
    });

    $(document).on('change', '.input-qty', function () {
        let idx = $(this).data('index');
        let val = parseInt($(this).val()) || 1;
        val = Math.max(1, Math.min(val, keranjang[idx].stok));
        keranjang[idx].jumlah = val;
        renderKeranjang();
    });

    $(document).on('click', '.btn-hapus', function () {
        let idx = $(this).data('index');
        keranjang.splice(idx, 1);
        renderKeranjang();
    });

    // ===================== HITUNG TOTAL & KEMBALIAN =====================
    function hitungTotal() {
        let total = keranjang.reduce(function (sum, item) {
            return sum + (item.harga_jual * item.jumlah);
        }, 0);

        $('#totalBelanja').val(formatRupiah(total));

        hitungKembalian();
        updateTombolSimpan();
    }

    function hitungKembalian() {
        let total = getTotal();
        let bayar = parseInt($('#uangBayar').val()) || 0;
        let kembalian = bayar - total;

        $('#kembalian').val(formatRupiah(Math.max(0, kembalian)));

        if (bayar > 0 && kembalian < 0) {
            $('#kembalian').removeClass('text-success').addClass('text-danger');
            $('#kembalian').val('-' + formatRupiah(Math.abs(kembalian)));
        } else {
            $('#kembalian').removeClass('text-danger').addClass('text-success');
        }

        updateTombolSimpan();
    }

    function getTotal() {
        return keranjang.reduce(function (sum, item) {
            return sum + (item.harga_jual * item.jumlah);
        }, 0);
    }

    function updateTombolSimpan() {
        let total = getTotal();
        let bayar = parseInt($('#uangBayar').val()) || 0;
        let valid = keranjang.length > 0 && bayar >= total && total > 0;
        $('#btnSimpan').prop('disabled', !valid);
    }

    $('#uangBayar').on('input', function () {
        hitungKembalian();
    });

    // ===================== TOMBOL UANG PAS =====================
    $(document).on('click', '.btn-uang-pas', function () {
        let nominal = $(this).data('nominal');
        if (nominal === 'uang-pas') {
            $('#uangBayar').val(getTotal());
        } else {
            $('#uangBayar').val(parseInt(nominal));
        }
        hitungKembalian();
    });

    // ===================== SIMPAN TRANSAKSI =====================
    $('#btnSimpan').on('click', function () {
        let total = getTotal();
        let bayar = parseInt($('#uangBayar').val()) || 0;

        if (keranjang.length === 0) {
            alert('Keranjang masih kosong!');
            return;
        }
        if (bayar < total) {
            alert('Uang bayar kurang!');
            return;
        }

        let $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

        $.ajax({
            url: 'api.php?action=simpan_transaksi',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                items: keranjang,
                bayar: bayar
            }),
            success: function (res) {
                if (res.success) {
                    // Buka struk di tab/popup baru
                    window.open('struk.php?id=' + res.data.id, '_blank', 'width=420,height=600,scrollbars=yes');

                    $('#suksesTotal').text('Rp ' + formatRupiah(res.data.total));
                    $('#suksesBayar').text('Rp ' + formatRupiah(res.data.bayar));
                    $('#suksesKembalian').text('Rp ' + formatRupiah(res.data.kembalian));
                    $('#modalSukses').modal('show');
                } else {
                    alert('Gagal: ' + res.message);
                }
            },
            error: function () {
                alert('Terjadi kesalahan jaringan.');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Simpan Transaksi');
            }
        });
    });

    // ===================== RESET =====================
    function resetKasir() {
        keranjang = [];
        renderKeranjang();
        $('#uangBayar').val(0);
        $('#kembalian').val('0');
        $('#kembalian').removeClass('text-danger').addClass('text-success');
        $('#cariBarang').val(null).trigger('change');
        updateTombolSimpan();
        setTimeout(fokusKeSearch, 150);
    }

    $('#btnReset').on('click', function () {
        if (keranjang.length === 0 || confirm('Bersihkan keranjang?')) {
            resetKasir();
        }
    });

    $('#btnTransaksiBaru').on('click', function () {
        $('#modalSukses').modal('hide');
        resetKasir();
    });

    // ===================== TAMBAH MANUAL =====================
    $('#btnTambahManual').on('click', function () {
        let nama = $('#manualNama').val().trim();
        let harga = parseInt($('#manualHarga').val()) || 0;
        let qty = parseInt($('#manualQty').val()) || 1;

        if (!nama) { alert('Nama barang harus diisi.'); $('#manualNama').focus(); return; }
        if (harga <= 0) { alert('Harga harus lebih dari 0.'); $('#manualHarga').focus(); return; }

        let $btn = $(this);
        $btn.prop('disabled', true);

        $.ajax({
            url: 'api.php?action=tambah_barang_manual',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ nama: nama, harga: harga }),
            success: function (res) {
                if (res.success) {
                    let item = res.barang;
                    // Jika harga dari DB beda (barang sudah ada), pakai harga DB
                    // Tambahkan ke keranjang dengan qty yang diminta
                    for (let i = 0; i < qty; i++) {
                        tambahKeKeranjang({
                            id: item.id,
                            nama: item.nama,
                            harga_jual: parseFloat(item.harga_jual),
                            stok: parseInt(item.stok),
                            satuan: item.satuan
                        });
                    }
                    // Reset form dan fokus ke cari barang
                    $('#manualNama').val('');
                    $('#manualHarga').val('');
                    $('#manualQty').val(1);
                    setTimeout(fokusKeSearch, 150);
                } else {
                    alert(res.message);
                }
            },
            error: function () { alert('Terjadi kesalahan.'); },
            complete: function () { $btn.prop('disabled', false); }
        });
    });

    // Enter di form manual = klik tambah
    $('#manualNama, #manualHarga, #manualQty').on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#btnTambahManual').click();
        }
    });

    // ===================== ESCAPE HTML =====================
    function escapeHtml(text) {
        let div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }
});
