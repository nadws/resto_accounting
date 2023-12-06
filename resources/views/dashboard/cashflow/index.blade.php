<div class="card">
    <div class="card-header">
        <form id="history_neraca">
            <div class="row">
                <div class="col-lg-6">
                    <h6 class="text-primary">Laporan Neraca </h6>
                </div>
                <div class="col-lg-3">

                </div>
                <div class="col-lg-2">
                    <select name="" class="select_cashflow">
                        <option value="2023">2023</option>
                    </select>
                </div>
                <div class="col-lg-1">
                    <button class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    <div id="loading_cashflow" class="spinner-border text-center text-success "
                        style="width: 6rem; height: 6rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="show_cashflow" style="display: none;">
            <div class="col-lg-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="dhead">Akun</th>
                            <th class="dhead text-end">Januari</th>
                            <th class="dhead text-end">Februari</th>
                            <th class="dhead text-end">Maret</th>
                            <th class="dhead text-end">April</th>
                            <th class="dhead text-end">Mei</th>
                            <th class="dhead text-end">Juni</th>
                            <th class="dhead text-end">Juli</th>
                            <th class="dhead text-end">Agustus</th>
                            <th class="dhead text-end">September</th>
                            <th class="dhead text-end">Oktober</th>
                            <th class="dhead text-end">November</th>
                            <th class="dhead text-end">Desember</th>
                            <th class="dhead text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">Uang Masuk <button type="button"
                                    class="btn btn-primary btn-sm btn-buka float-end"
                                    @click="open_umasuk = ! open_umasuk"><i class="fas fa-caret-down"></i></button></td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                            <td class="text-end fw-bold">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
