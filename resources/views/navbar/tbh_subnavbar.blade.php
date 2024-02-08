
<x-theme.app title="No Table" table="T">
    <x-slot name="slot">
        <form action="{{ route('createNavbar') }}" method="post">
            @csrf
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="">Navbar</label>
                    <input type="text" name="navbar" class="form-control">
                </div>
            </div>
            <x-theme.multiple-input>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="">Judul</label>
                            <input type="text" name="judul[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="">Route</label>
                            <input type="text" name="route[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="">Img</label>
                            <input type="text" name="img[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="">Deskripsi</label>
                            <input type="text" name="deskripsi[]" class="form-control">
                        </div>
                    </div>
                
            </x-theme.multiple-input>
            <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
        </form>
    </x-slot>

</x-theme.app>
