<x-app-layout>
    <x-slot name="header">
        Manajemen Sales Order (SO)
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center">
                    <div class="bg-info-subtle text-info rounded p-2 me-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-globe-americas fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Buat Sales Order Baru (Ekspor)</h5>
                        <p class="text-muted small mb-0">Input detail permintaan barang dari klien luar negeri</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="/sales-order/store" method="POST">
                        @csrf
                        
                        <!-- Header SO Auto-generated -->
                        <div class="row bg-light p-3 rounded-3 mb-4 mx-0 align-items-center">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="text-muted small fw-semibold d-block mb-1">Nomor Sales Order</label>
                                <input type="text" class="form-control form-control-sm bg-white fw-bold text-primary" value="SO-EXP-{{ date('Ym') }}-042" readonly>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="text-muted small fw-semibold d-block mb-1">Tanggal Order</label>
                                <input type="date" name="order_date" class="form-control form-control-sm bg-white" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small fw-semibold d-block mb-1">Estimasi Pengiriman (ETD)</label>
                                <input type="date" name="estimated_delivery" class="form-control form-control-sm bg-white" required>
                            </div>
                        </div>

                        <!-- Informasi Klien & Pengiriman Ekspor -->
                        <h6 class="fw-bold text-secondary text-uppercase small mb-3 border-bottom pb-2">Informasi Klien & Tujuan</h6>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Nama Klien / Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light border-0" name="customer_name" placeholder="Contoh: Starbucks Roastery Euro" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Negara Tujuan <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-0" name="destination_country" required>
                                    <option value="" selected disabled>Pilih Negara Tujuan...</option>
                                    <option value="US">Amerika Serikat (USA)</option>
                                    <option value="DE">Jerman (Germany)</option>
                                    <option value="JP">Jepang (Japan)</option>
                                    <option value="AU">Australia</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label fw-semibold small">Pelabuhan Tujuan (Port of Discharge) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light border-0" name="port_of_discharge" placeholder="Contoh: Port of Hamburg" required>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label fw-semibold small">Incoterms <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-0" name="incoterms" required>
                                    <option value="FOB">FOB (Free On Board)</option>
                                    <option value="CIF">CIF (Cost, Insurance, and Freight)</option>
                                    <option value="EXW">EXW (Ex Works)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Metode Pengiriman</label>
                                <select class="form-select bg-light border-0" name="shipping_method" required>
                                    <option value="Sea Freight">Sea Freight (FCL/LCL)</option>
                                    <option value="Air Freight">Air Freight</option>
                                </select>
                            </div>
                        </div>

                        <!-- Item Pesanan -->
                        <div class="d-flex justify-content-between align-items-end border-bottom pb-2 mb-3 mt-5">
                            <h6 class="fw-bold text-secondary text-uppercase small mb-0">Detail Item Pesanan</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill fw-semibold">
                                <i class="bi bi-plus-lg"></i> Tambah Baris
                            </button>
                        </div>
                        
                        <div class="table-responsive mb-4">
                            <table class="table table-borderless align-middle">
                                <thead class="bg-light rounded">
                                    <tr>
                                        <th class="small fw-semibold text-secondary" style="width: 50%;">Pilih Barang (SKU)</th>
                                        <th class="small fw-semibold text-secondary" style="width: 25%;">Kuantitas (Qty)</th>
                                        <th class="small fw-semibold text-secondary" style="width: 20%;">Satuan</th>
                                        <th class="small fw-semibold text-secondary text-center" style="width: 5%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Baris Item 1 -->
                                    <tr class="border-bottom">
                                        <td class="ps-0">
                                            <select class="form-select bg-light border-0" name="items[0][product_id]" required>
                                                <option value="" selected disabled>Pilih Produk Ekspor...</option>
                                                <option value="1">RAW-GB-GAYO-G1 - Green Beans Arabica Gayo</option>
                                                <option value="2">RAW-GB-LMP-R1 - Green Beans Robusta Lampung</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control bg-light border-0" name="items[0][qty]" placeholder="0" min="1" required>
                                        </td>
                                        <td>
                                            <select class="form-select bg-light border-0" name="items[0][unit]">
                                                <option value="Kg">Kilogram (Kg)</option>
                                                <option value="Ton">Metric Ton (MT)</option>
                                                <option value="Container">Container 20ft</option>
                                            </select>
                                        </td>
                                        <td class="text-center pe-0">
                                            <button type="button" class="btn btn-sm btn-light text-danger shadow-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Instruksi Khusus (Optional)</label>
                            <textarea class="form-control bg-light border-0" name="special_instructions" rows="3" placeholder="Contoh: Dokumen Phytosanitary Certificate harus disertakan..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                            <button type="reset" class="btn btn-light border px-4 me-2 fw-semibold">Batal</button>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Sales Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>