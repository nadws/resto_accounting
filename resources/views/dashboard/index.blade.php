<x-theme.app title="{{ $title }}" table="T">
    <x-slot name="slot">

        <div class="row">
            <div class="col-lg-8 mb-2">
                <h6>Dashboard</h6>
            </div>

            <div class="col-lg-12">
                <div id="load_profit"></div>
                @php
                    $form = [
                        1 => ['tbhBiaya', 'Biaya'],
                        2 => ['tbhPendapatan', 'Pendapatan'],
                        3 => ['tbhBiayaPenyesuaian', 'Biaya Penyesuaian'],
                        4 => ['tbhBiayaDisusutkan', 'Biaya Disusutkan'],
                    ];
                @endphp
                @foreach ($form as $d => $i)
                    <form id="save_akun_profit">
                        <x-theme.modal title="Tambah Akun Profit Pendapatan" size="modal-lg"
                            idModal="{{ $i[0] }}">

                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="">Nama akun</label>
                                    <input type="text" class="form-control" name="nm_akun[]" required>
                                </div>
                                <div class="col-lg-3">
                                    <label for="">Nomer akun</label>
                                    <input type="text" class="form-control" name="kode_akun[]" required>
                                </div>
                                <div class="col-lg-3">
                                    <label for="">Kategori</label>
                                    <input type="hidden" name="id_klasifikasi[]" value="{{ $d }}">
                                    <input type="text" class="form-control" readonly value="{{ $i[1] }}">
                                </div>
                            </div>
                            <x-theme.multiple-input>
                                <div class="col-lg-4">
                                    <label for="">Nama akun</label>
                                    <input type="text" class="form-control" name="nm_akun[]" required>
                                </div>
                                <div class="col-lg-3">
                                    <label for="">Nomer akun</label>
                                    <input type="text" class="form-control" name="kode_akun[]" required>
                                </div>
                                <div class="col-lg-3">
                                    <label for="">Kategori</label>
                                    <input type="hidden" name="id_klasifikasi[]" value="{{ $d }}">
                                    <input type="text" class="form-control" readonly value="{{ $i[1] }}">
                                </div>
                            </x-theme.multiple-input>
                        </x-theme.modal>
                    </form>
                @endforeach

            </div>
            <div class="col-lg-12">
                <div id="load_cashflow"></div>
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
            load_cashflow();

            function toast(pesan) {
                Toastify({
                    text: pesan,
                    duration: 3000,
                    position: 'center',
                    style: {
                        background: "#EAF7EE",
                        color: "#7F8B8B"
                    },
                    close: true,
                    avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                }).showToast();
            }
            load_profit()

            function load_profit() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.index') }}",
                    success: function(r) {
                        $("#load_profit").html(r);
                    }
                });
            }

            function load_neraca(bulan, tahun) {
                $.ajax({
                    type: "get",
                    url: "{{ route('neraca.index') }}",
                    data: {
                        bulan: bulan,
                        tahun: tahun,
                    },
                    success: function(response) {
                        $("#load_neraca").html(response);
                        setTimeout(function() {
                            $('#loading2').hide();
                            $('#show2').show();
                        }, 1000);
                        $('.select').select2({
                            language: {
                                searching: function() {
                                    $('.select2-search__field').focus();
                                }
                            }
                        });
                    }
                });
            }

            function load_cashflow(bulan, tahun) {
                $.ajax({
                    type: "get",
                    url: "{{ route('cashflow.index') }}",
                    data: {
                        bulan: bulan,
                        tahun: tahun,
                    },
                    success: function(response) {
                        $("#load_cashflow").html(response);
                        setTimeout(function() {
                            $('#loading_cashflow').hide();
                            $('#show2').show();
                        }, 1000);
                        $('.select').select2({
                            language: {
                                searching: function() {
                                    $('.select2-search__field').focus();
                                }
                            }
                        });
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
                        setTimeout(function() {
                            $('#loading').hide();
                            $('#show').show();
                        }, 1000);
                    }
                });
            }

            $(document).on('submit', '#save_akun', function(event) {
                event.preventDefault(); // Prevent the default form submission

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var formData = $(this).serialize();
                formData += "&_token=" + csrfToken;

                $('#loading').show();
                $('#show').hide();
                $('#loading2').show();
                $('#show2').hide();

                $.ajax({
                    url: "{{ route('akun.save') }}", // Replace with your Laravel route
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        toast('Akun berhasil di simpan')
                        load_neraca();
                        load_akun();
                        setTimeout(function() {
                            $('#loading').hide();
                            $('#show').show();
                            $('#loading2').hide();
                            $('#show2').show();
                        }, 1000);
                        $("#tambah").modal('hide');
                    },
                    error: function(xhr) {
                        // Handle error response
                        toast('Data gagal di simpan')
                    }
                });
            });
            $(document).on('submit', '#history_neraca', function(event) {
                event.preventDefault(); // Prevent the default form submission

                var bulan = $("#bulan").val();
                var tahun = $("#tahun").val();



                load_neraca(bulan, tahun);

            });

            $(document).on('submit', '#save_akun_profit', function(e) {
                e.preventDefault()
                var formData = $(this).serialize();
                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.createAkun') }}",
                    data: formData,
                    success: function(response) {
                        toast('Akun berhasil di simpan')
                        load_neraca();
                        load_akun();
                        load_profit()
                        $("#tbhBiaya, #tbhPendapatan, #tbhBiayaPenyesuaian, #tbhBiayaDisusutkan").modal(
                            'hide');
                    }
                });
            })
        </script>
    @endsection
</x-theme.app>
