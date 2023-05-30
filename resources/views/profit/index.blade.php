<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <h6 class="float-start mt-1">{{ $title }}: {{ tanggal($tgl1) }} ~
            {{ tanggal($tgl2) }}</h6>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                {{-- <a href="{{ route('export_jurnal', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"
                    class="float-end btn   btn-success me-2"><i class="fas fa-file-excel"></i> Export</a> --}}
                <a target="_blank" href="{{ route('profit.print', ['tgl1' => $tgl1, 'tgl2' => $tgl2]) }}"
                    class="float-end btn   btn-primary me-2"><i class="fas fa-print"></i> Print</a>
                <x-theme.button modal="Y" idModal="view" icon="fa-filter" addClass="float-end" teks="" />
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <div id="tableLoad"></div>
        <form id="formtabhAkun">
            <x-theme.modal title="Add Akun" idModal="tambah-profit" size="modal-lg">
                <div id="modalLoad"></div>
            </x-theme.modal>
        </form>

        <x-theme.modal title="Add Uraian" idModal="tambah-uraian" size="modal-lg">
            <div class="uraian-modal"></div>
        </x-theme.modal>

        <form action="" method="get">
            <x-theme.modal title="Filter Profit & Loss" idModal="view">
                <div class="row">
                    <div class="col-lg-12">

                        <table width="100%" cellpadding="10px">
                            <tr>
                                <td>Tanggal</td>
                                <td>
                                    <label for="">Dari</label>
                                    <input type="date" name="tgl1" class="form-control">
                                </td>
                                <td>
                                    <label for="">Sampai</label>
                                    <input type="date" name="tgl2" class="form-control">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </x-theme.modal>
        </form>

        {{--  --}}
    </x-slot>
    @section('scripts')
        <script>
            loadTabel()
            loadModal()

            function loadTabel(tgl1 = "{{ date('Y-m-1') }}", tgl2 = "{{ date('Y-m-d') }}") {
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

            function loadModal() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.modal') }}",
                    success: function(r) {
                        $("#modalLoad").html(r);
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

                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.add') }}",
                    data: {
                        id_akun: id_akun,
                        urutan: urutan,
                    },
                    success: function(r) {
                        $('#tambah-profit').off('hide.bs.modal');;
                        toast('Berhasil tambah akun')
                        loadModal()
                        loadTabel()
                    }
                });
            })

            $(document).on('click', '.btnHapus', function() {
                var id_profit = $(this).attr("id_profit")

                $.ajax({
                    type: "GET",
                    url: "{{ route('profit.delete') }}",
                    data: {
                        id_profit: id_profit,
                    },
                    success: function(r) {
                        toast('Berhasil hapus akun')
                        loadModal()
                        loadTabel()
                    }
                });
            })
        </script>
    @endsection
</x-theme.app>
