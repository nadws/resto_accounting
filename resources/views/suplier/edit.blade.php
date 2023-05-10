<div class="row">
    <input type="hidden" value="{{ $suplier->id_suplier }}" name="id_suplier">
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Nama</label>
            <input value="{{ $suplier->nm_suplier }}" type="text" name="nm_suplier" class="form-control">
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Email</label>
            <input value="{{ $suplier->email }}" type="email" name="email" class="form-control">
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group">
            <label for="">Telepon</label>
            <input value="{{ $suplier->telepon }}" type="text" name="telepon" class="form-control">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Npwp</label>
            <input value="{{ $suplier->npwp }}" type="text" name="npwp" class="form-control">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="">Unggah dokumen</label>
            <input value="{{ $suplier->dokumen }}" type="file" class="form-control" name="img" id="image" accept="image/*">
        </div>
    </div>
    <div class="col-lg-12">
        <div id="image-preview" class="float-end"></div>
    </div>
</div>