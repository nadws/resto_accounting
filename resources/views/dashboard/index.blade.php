<x-theme.app title="{{ $title }}" table="T">
    <x-slot name="slot">

        <div class="row">
            <div class="col-lg-8 mb-2">
                <h6>Dashboard</h6>
            </div>
            <div class="col-lg-6">
                <div id="load_akun"></div>
            </div>
            <div class="col-lg-6">
                <div id="load_neraca"></div>
            </div>
        </div>

        <form id="save_akun">
            <x-theme.modal title="Tambah Akun" idModal="tambah">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="">Nama akun</label>
                        <input type="text" class="form-control" name="nm_akun" required>
                    </div>
                    <div class="col-lg-4">
                        <label for="">Nomer akun</label>
                        <input type="text" class="form-control" name="kode_akun" required>
                    </div>
                    <div class="col-lg-4">
                        <label for="">Kategori</label>
                        <select name="id_klasifikasi" id="" class="select2" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategori_akun as $k)
                                <option value="{{ $k->id_subklasifikasi_akun }}">{{ $k->nm_subklasifikasi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </x-theme.modal>
        </form>
    </x-slot>

    @section('scripts')
        <script>
            load_neraca();
            load_akun();

            function toast(pesan) {
                Toastify({
                    text: pesan,
                    duration: 3000,
                    style: {
                        background: "#EAF7EE",
                        color: "#7F8B8B"
                    },
                    close: true,
                    avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                }).showToast();
            }

            function load_neraca() {
                $.ajax({
                    type: "get",
                    url: "{{ route('neraca.index') }}",
                    success: function(response) {
                        $("#load_neraca").html(response);
                    }
                });
            }

            function load_akun() {
                $.ajax({
                    type: "get",
                    url: "{{ route('akun.index') }}",
                    success: function(response) {
                        $("#load_akun").html(response);
                        $('#table_akun').DataTable({
                            "paging": true,
                            "pageLength": 10,
                            "lengthChange": true,
                            "stateSave": true,
                            "searching": true,
                        });
                    }
                });
            }

            $(document).on('submit', '#save_akun', function(event) {
                event.preventDefault(); // Prevent the default form submission

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var formData = $(this).serialize();
                formData += "&_token=" + csrfToken;

                $.ajax({
                    url: "{{ route('akun.save') }}", // Replace with your Laravel route
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        toast('Akun berhasil di simpan')
                        load_neraca();
                        load_akun();
                        $("#tambah").modal('hide');
                    },
                    error: function(xhr) {
                        // Handle error response
                        toast('Data gagal di simpan')
                    }
                });
            });
        </script>
    @endsection
</x-theme.app>
