<h4 class="mb-3"><i class="bi bi-calculator"></i> Kasir</h4>

<div class="row">
    <!-- Kolom Kiri: Cari Barang + Keranjang -->
    <div class="col-lg-8 mb-4">
        <!-- Tambah Manual -->
        <div class="mb-3">
            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center"
                    type="button" data-bs-toggle="collapse" data-bs-target="#formManual">
                <span><i class="bi bi-pencil-square"></i> Barang tidak ada? Tambah manual</span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <div class="collapse mt-2" id="formManual">
                <div class="card shadow-sm"><div class="card-body">
                    <div class="row g-2 align-items-end">
                        <div class="col">
                            <label class="form-label form-label-sm">Nama Barang</label>
                            <input type="text" id="manualNama" class="form-control form-control-sm" placeholder="Nama barang" required>
                        </div>
                        <div class="col-auto" style="width:120px;">
                            <label class="form-label form-label-sm">Harga</label>
                            <input type="number" id="manualHarga" class="form-control form-control-sm" placeholder="0" min="0" step="500">
                        </div>
                        <div class="col-auto" style="width:70px;">
                            <label class="form-label form-label-sm">Qty</label>
                            <input type="number" id="manualQty" class="form-control form-control-sm" value="1" min="1">
                        </div>
                        <div class="col-auto">
                            <button type="button" id="btnTambahManual" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-lg"></i> Tambah
                            </button>
                        </div>
                    </div>
                    <div class="form-text mt-1">Barang otomatis ditambahkan ke daftar barang jika belum ada.</div>
                </div></div>
            </div>
        </div>

        <!-- Search -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <label class="form-label fw-bold"><i class="bi bi-search"></i> Cari Barang</label>
                <select id="cariBarang" class="form-control" style="width: 100%;">
                    <option></option>
                </select>
            </div>
        </div>

        <!-- Keranjang -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cart3"></i> Keranjang Belanja</span>
                <span class="badge bg-light text-success" id="badgeTotal">0 item</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="tabelKeranjang">
                        <thead class="table-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Nama Barang</th>
                                <th class="text-end" width="130">Harga</th>
                                <th class="text-center" width="120">Qty</th>
                                <th class="text-end" width="140">Subtotal</th>
                                <th class="text-center" width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="bodyKeranjang">
                            <tr id="rowKosong">
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-cart-x" style="font-size: 2rem;"></i>
                                    <p class="mb-0 mt-1">Keranjang masih kosong</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Ringkasan Pembayaran -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-success">
            <div class="card-header bg-success text-white">
                <i class="bi bi-cash-coin"></i> Pembayaran
            </div>
            <div class="card-body">
                <!-- Total -->
                <div class="mb-3">
                    <label class="form-label text-muted">Total Belanja</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">Rp</span>
                        <input type="text" id="totalBelanja" class="form-control fw-bold text-end fs-4"
                               value="0" readonly>
                    </div>
                </div>

                <!-- Bayar -->
                <div class="mb-3">
                    <label class="form-label text-muted">Uang Bayar</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="uangBayar" class="form-control text-end fs-5"
                               min="0" step="500" value="0" placeholder="0">
                    </div>
                </div>

                <!-- Kembalian -->
                <div class="mb-3">
                    <label class="form-label text-muted">Kembalian</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">Rp</span>
                        <input type="text" id="kembalian" class="form-control fw-bold text-end fs-4"
                               value="0" readonly>
                    </div>
                </div>

                <!-- Tombol Uang Pas -->
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-uang-pas" data-nominal="uang-pas">Uang Pas</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-uang-pas" data-nominal="10000">10rb</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-uang-pas" data-nominal="20000">20rb</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-uang-pas" data-nominal="50000">50rb</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-uang-pas" data-nominal="100000">100rb</button>
                </div>

                <hr>

                <!-- Simpan -->
                <div class="d-grid gap-2">
                    <button type="button" id="btnSimpan" class="btn btn-success btn-lg" disabled>
                        <i class="bi bi-check-circle"></i> Simpan Transaksi
                    </button>
                    <button type="button" id="btnReset" class="btn btn-outline-danger">
                        <i class="bi bi-arrow-counterclockwise"></i> Bersihkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sukses -->
<div class="modal fade" id="modalSukses" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-check-circle"></i> Transaksi Berhasil</h5>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                <h4 class="mt-3" id="suksesMessage">Transaksi berhasil disimpan!</h4>
                <div class="row mt-3">
                    <div class="col-4 text-end text-muted">Total:</div>
                    <div class="col-8 text-start fw-bold" id="suksesTotal">-</div>
                </div>
                <div class="row">
                    <div class="col-4 text-end text-muted">Bayar:</div>
                    <div class="col-8 text-start fw-bold" id="suksesBayar">-</div>
                </div>
                <div class="row">
                    <div class="col-4 text-end text-muted">Kembalian:</div>
                    <div class="col-8 text-start fw-bold fs-5 text-success" id="suksesKembalian">-</div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success btn-lg" id="btnTransaksiBaru">
                    <i class="bi bi-plus-circle"></i> Transaksi Baru
                </button>
            </div>
        </div>
    </div>
</div>
