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


            </div>
        </div>
    </div>
</div>
