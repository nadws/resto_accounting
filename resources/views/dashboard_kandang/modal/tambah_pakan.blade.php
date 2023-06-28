{{-- modal --}}
<form id="form_tambah_pakan" method="post">
    @csrf
    <x-theme.modal title="Tambah Perencanaan" idModal="tambah_pakan">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="">Kode Pakan</label>
                    <input type="text" name="kd_pakan" class="form-control">
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    <label for="">Nama Pakan</label>
                    <input required type="text" name="nm_pakan" class="form-control">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="">Stok Awal</label>
                    <input type="text" name="stok_awal" class="form-control">
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    <label for="">Total Rp</label>
                    <input type="text" name="total_rp" class="form-control">
                </div>
            </div>
        </div>
    </x-theme.modal>
</form>
{{-- end modal --}}