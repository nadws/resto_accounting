<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <div class="col-lg-6">
            <h6 class="float-start mt-1">{{ $title }}: {{date('d-m-Y',strtotime($tgl1))}} ~
                {{date('d-m-Y',strtotime($tgl2))}}</h6>
        </div>
        <x-theme.button modal="T" icon="fa-print" href="/print_cashflow?tgl1={{$tgl1}}&tgl2={{$tgl2}}" variant="success"
            addClass="float-end" teks="Print" />
        <x-theme.button modal="Y" idModal="daftarakun" icon="fa-book" variant="primary" addClass="float-end view_akun"
            teks="Daftar Akun" />
        <x-theme.btn_filter />
    </x-slot>
    <x-slot name="cardBody">
        <div id="loadcontrolflow"></div>



        {{-- form sub kategori --}}

        <x-theme.modal title="Kategori" size="modal-lg" btnSave='T' idModal="modalPendapatan">
            <div id="loadPendapatan"></div>
        </x-theme.modal>

        <x-theme.modal title="Pilih Akun" size="modal-lg" btnSave='T' idModal="modalAkunPendapatan">
            <div id="loadAkunPendapatan"></div>
        </x-theme.modal>

        <x-theme.modal title="Daftar Akun yang belum terdaftar" size="modal-lg" btnSave='T' idModal="daftarakun">
            <div id="viewdaftarakun"></div>
        </x-theme.modal>



        {{-- end form sub kategori --}}
    </x-slot>

    @section('scripts')
    <script>
        loadTabel()
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
        function loadTabel(tgl1 = "{{$tgl1}}", tgl2 = "{{ $tgl2 }}") {
            $.ajax({
                type: "GET",
                url: "{{ route('loadcontrolflow') }}",
                data: {
                    tgl1: tgl1,
                    tgl2: tgl2,
                },
                success: function(r) {
                    $("#loadcontrolflow").html(r);

                }
            });
        }
        function loadInputAkun(jenis) {
            $.ajax({
                type: "GET",
                url: "{{ route('loadInputAkunCashflow') }}",
                data: {
                    jenis:jenis
                },
                success: function(r) {
                    $("#loadPendapatan").html(r);
                    $('.jenisSub').val(jenis)
                    $('.select').select2({
                        dropdownParent: $('#modalPendapatan .modal-content')
                    });
                }
            });
        }
        function loadInputsub(id_kategori,tgl1 = "{{$tgl1}}", tgl2 = "{{ $tgl2 }}") {
            $.ajax({
                type: "GET",
                url: "{{ route('loadInputsub') }}",
                data: {
                    id_kategori:id_kategori,
                    tgl1:tgl1,
                    tgl2:tgl2
                },
                success: function(r) {
                    $("#loadAkunPendapatan").html(r);
                    // $('.jenisSub').val(jenis)

                    $('.select').select2({
                        dropdownParent: $('#modalAkunPendapatan .modal-content')
                    });
                }
            });
        }
        $(document).on('click', '.input_pendapatan', function() {
            $("#modalPendapatan").modal('show')
            var jenis = $(this).attr('jenis');
            loadInputAkun(jenis)
        });
        $(document).on('submit', '#formTambahAkun', function(e) {
            e.preventDefault()
            var data = $("#formTambahAkun").serialize()
            var jenis = $('.jenis').val();
            $.ajax({
                type: "GET",
                url: "{{ route('save_kategoriCashcontrol') }}?" + data,
                success: function(response) {
                    toast('Berhasil tambah Kategori')
                    loadInputAkun(jenis)
                    loadTabel()
                    // $("#modalSubKategori").modal('hide')
                }
            });
        });
        $(document).on('submit', '#Editinputanakun', function(e) {
            e.preventDefault()
            var data = $("#Editinputanakun").serialize()
            var jenis = $('.jenis').val();
            $.ajax({
                type: "GET",
                url: "{{ route('edit_kategoriCashcontrol') }}?" + data,
                success: function(response) {
                    toast('Berhasil edit Kategori')
                    loadInputAkun(jenis)
                    loadTabel()
                    // $("#modalSubKategori").modal('hide')
                }
            });
        });
        $(document).on('click', '.tmbhakun', function() {
            var id_kategori = $(this).attr('id_kategori');
            $("#modalAkunPendapatan").modal('show');
            loadInputsub(id_kategori);
        });
        $(document).on('submit', '#formTambahSubAkun', function(e) {
            e.preventDefault()
            var data = $("#formTambahSubAkun").serialize()
            var id_kategori = $('.id_kategori').val();
            $.ajax({
                type: "GET",
                url: "{{ route('SaveSubAkunCashflow') }}?" + data,
                success: function(response) {
                    toast('Berhasil tambah Akun')
                    loadInputsub(id_kategori);
                    loadTabel()
                    // $("#modalSubKategori").modal('hide')
                }
            });
        });
        $(document).on('click', '.delete_akun', function() {
            var id_akuncontrol = $(this).attr('id_akuncontrol');
            var id_kategori = $(this).attr('id_kategori');
            $.ajax({
                type: "GET",
                url: "{{ route('deleteSubAkunCashflow') }}?id_akuncontrol=" + id_akuncontrol,
                success: function(response) {
                    toast('Akun berhasil di hapus')
                    loadInputsub(id_kategori);
                    loadTabel()
                    // $("#modalSubKategori").modal('hide')
                }
            });
        });
        $(document).on('click', '.delete_kategori_akun', function() {
            var id_kategori = $(this).attr('id_kategori_cashcontrol');
            var jenis = $(this).attr('jenis');
            $.ajax({
                type: "GET",
                url: "{{ route('deleteAkunCashflow') }}?id_kategori=" + id_kategori,
                success: function(response) {
                    toast('Akun berhasil di hapus')
                    loadInputAkun(jenis);
                    loadTabel()
                    // $("#modalSubKategori").modal('hide')
                }
            });
        });
        $(document).on('click', '.view_akun', function() {
            $.ajax({
                type: "GET",
                url: "{{ route('view_akun') }}",
                success: function(data) {
                    $("#viewdaftarakun").html(data);
                    $("#table2").DataTable({
                        "lengthChange": false,
                        "autoWidth": false,
                        "stateSave": true,
                    });
                }
            });
        });
    </script>

    @endsection
</x-theme.app>