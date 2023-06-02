<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <h6 class="float-start mt-1">{{ $title }}: {{ tanggal($tgl1) }} ~
            {{ tanggal($tgl2) }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <x-theme.button modal="T" href="/profit/print?tgl1={{ $tgl1 }}&tgl2={{ $tgl2 }}"
                    icon="fa-print" addClass="float-end" teks="Print" />

                <x-theme.btn_filter />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <div id="tableLoad"></div>
        <form id="formtabhAkun">
            <x-theme.modal btnSave="T" title="Tambah Akun" idModal="tambah-profit" size="modal-lg">
                <div id="modalLoad"></div>
            </x-theme.modal>
        </form>

        <form action="" id="formUraian">
            <x-theme.modal btnSave="T" title="Tambah Uraian" idModal="tambah-uraian" size="modal-lg">
                <div class="uraian-modal"></div>
            </x-theme.modal>
        </form>

        {{--  --}}
    </x-slot>
    @section('scripts')
        <script>
            loadTabel()

            function loadTabel(tgl1 = "{{ $tgl1 }}", tgl2 = "{{ $tgl2 }}") {
                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.load') }}",
                    data: {
                        tgl1: tgl1,
                        tgl2: tgl2,
                    },
                    success: function(r) {
                        $("#tableLoad").html(r);
                    }
                });
            }

            function loadUraianModal(jenis) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.load_uraian') }}",
                    data: {
                        jenis: jenis
                    },
                    success: function(r) {
                        $(".uraian-modal").html(r);
                        $('.jenisSub').val(jenis)
                    }
                });
            }

            function loadModal(id_kategori) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.modal') }}",
                    data: {
                        'id_kategori': id_kategori
                    },
                    success: function(r) {
                        $("#modalLoad").html(r);
                        $('#kategori_idInput').val(id_kategori)
                        $('#table').DataTable({
                            "paging": true,
                            "pageLength": 10,
                            "lengthChange": true,
                            "stateSave": true,
                            "searching": true,
                        });
                        $('.select2-profit').select2({
                            dropdownParent: $('#tambah-profit .modal-content')
                        });
                    }
                });
            }

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

            $(document).on('click', '.klikModal', function(e) {
                e.preventDefault();
                var id_kategori = $(this).attr('id_kategori')

                loadModal(id_kategori)
                $("#tambah-profit").modal('show')
            })

            $(document).on('click', '.uraian', function() {
                var jenis = $(this).attr('jenis')
                loadUraianModal(jenis)
            })

            $(document).on('click', '#btnFormSubKategori', function() {
                var jenisSub = $('.jenisSub').val();
                var urutan = $('.urutanInput').val();
                var sub_kategori = $('.sub_kategoriInput').val();

                $.ajax({
                    method: "GET",
                    url: "{{ route('profit.save_subkategori') }}",
                    data: {
                        jenis: jenisSub,
                        urutan: urutan,
                        sub_kategori: sub_kategori
                    },
                    success: function(r) {
                        toast('Berhasil save kategori')
                        loadUraianModal(jenisSub)
                        loadTabel()
                    }
                });
            })

            $(document).on('click', '.btnDeleteSubKategori', function() {
                var id = $(this).attr('id')
                var jenis = $(this).attr('id_jenis')
                if (confirm('Yakin ingin dihapus ? ')) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('profit.delete_subkategori') }}",
                        data: {
                            id: id
                        },
                        success: function(r) {
                            toast('Berhasil hapus kategori')
                            loadUraianModal(jenis)
                            loadTabel()
                        }
                    });
                }
            })

            $(document).on('click', '#btnSave', function() {
                var id_akun = $("#id_akun").val()
                var urutan = $("#urutan").val()
                var kategori_idInput = $("#kategori_idInput").val()

                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.add') }}",
                    data: {
                        id_akun: id_akun,
                        urutan: urutan,
                        kategori_id: kategori_idInput,
                    },
                    success: function(r) {
                        $('#tambah-profit').off('hide.bs.modal');;
                        toast('Berhasil tambah akun')
                        loadModal(kategori_idInput)
                        loadTabel()
                    }
                });
            })

            $(document).on('click', '.btnHapus', function() {
                var id_profit = $(this).attr("id_profit")
                var id_kategori = $(this).attr("id_kategori")

                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.delete') }}",
                    data: {
                        id_profit: id_profit,
                    },
                    success: function(r) {
                        toast('Berhasil hapus akun')
                        loadModal(id_kategori)
                        loadTabel()
                    }
                });
            })

            $(document).on('submit', '#formUraian', function(e){
                e.preventDefault()
                var formVal = $("#formUraian").serialize()
                var jenisSub = $(".jenisSub").val()
                
                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.update') }}?"+formVal,
                    success: function(r) {
                        toast('Berhasil update kategori')
                        loadUraianModal(jenisSub)
                        loadTabel()
                    }
                });
            })
        </script>
    @endsection
</x-theme.app>
