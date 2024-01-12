<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
        <h6 class="text-success float-start">Daftar Akun</h6>
        <x-theme.button modal="Y" idModal="tambah" icon="fa-plus" variant="primary" addClass="float-end"
            teks="Akun" />
    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <div id="load_akun"></div>

        </section>
        <form id="save_akun">
            <x-theme.modal title="Tambah Akun" size="modal-lg" idModal="tambah">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="">Nama akun</label>
                        <input type="text" class="form-control" name="nm_akun" required>
                    </div>
                    <div class="col-lg-3">
                        <label for="">Inisial</label>
                        <input type="text" class="form-control" name="inisial" required>
                    </div>
                    <div class="col-lg-2">
                        <label for="">Nomer akun</label>
                        <input type="text" class="form-control" name="kode_akun" required>
                    </div>
                    <div class="col-lg-3">
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
        <form id="formEditAkun">
            <x-theme.modal title="Edit Akun" size="modal-lg" idModal="edit_akun">
                <div id="load_edit_akun"></div>
            </x-theme.modal>
        </form>
        <form id="formTmbhPostCenter">
            <x-theme.modal title="Post Center" btnSave="T" idModal="post_center">
                <div id="load_post_center"></div>
            </x-theme.modal>
        </form>

        <form id="form_edit_post">
            <x-theme.modal title="Edit Post Center" idModal="edit_post">
                <div id="load_edit_post_center"></div>
            </x-theme.modal>
        </form>
        @section('scripts')
            <script>
                $(document).ready(function() {
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
                    load_akun()

                    function load_akun() {
                        $.ajax({
                            type: "get",
                            url: "{{ route('akun.load') }}",
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

                    $(document).on('click', '.edit_akun', function() {
                        var id_akun = $(this).attr('id_akun')
                        $("#edit_akun").modal('show')

                        $.ajax({
                            type: "GET",
                            url: "{{ route('akun.edit_load') }}",
                            data: {
                                id_akun: id_akun
                            },
                            success: function(response) {
                                $('#load_edit_akun').html(response);

                                $('.select-edit').select2({
                                    dropdownParent: $('#edit_akun .modal-content')
                                })
                            }
                        });
                    })

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
                                toast('Akun berhasil di simpan')
                                load_akun();
                                $("#tambah").modal('hide');
                            },
                            error: function(xhr) {
                                // Handle error response
                                toast('Data gagal di simpan')
                            }
                        });
                    });

                    $(document).on('submit', '#formEditAkun', function(e) {
                        e.preventDefault()
                        var datas = $(this).serialize()
                        $.ajax({
                            type: "GET",
                            url: "{{route('akun.update')}}",
                            data: datas,
                            success: function (response) {
                                load_akun()
                                $("#edit_akun").modal('hide')
                                toast('berhasil edit')

                            }
                        });
                    })

                    $(document).on('click', '.hapus_akun', function(e){
                        e.preventDefault()
                        var id_akun = $(this).attr('id_akun')
                        $.ajax({
                            type: "GET",
                            url: "{{route('akun.hapus')}}?id_akun="+id_akun,
                            success: function (r) {
                                load_akun()
                                toast(r)
                            }
                        });
                    })

                    function load_post_center(id_akun) {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('akun.post_center') }}?id_akun=" + id_akun,
                            success: function(r) {
                                $("#load_post_center").html(r);
                                $('#tblPost').DataTable({
                                    "paging": true,
                                    "pageLength": 10,
                                    "lengthChange": true,
                                    "ordering": false,
                                    "searching": true,
                                });
                            }
                        });
                    }
                    $(document).on('click', '.post_center', function() {
                        var id_akun = $(this).attr('id_akun')
                        $("#post_center").modal('show')
                        load_post_center(id_akun)
                    })
                    $(document).on('submit', '#formTmbhPostCenter', function(e) {
                        e.preventDefault()
                        var datas = $(this).serialize()
                        $.ajax({
                            type: "GET",
                            url: "{{ route('akun.create_post_center') }}",
                            data: datas,
                            success: function(r) {
                                load_post_center(r)

                            }
                        });
                    })
                    $(document).on('click', '.edit_post', function(e) {
                        e.preventDefault()
                        var id_post = $(this).attr('id_post')
                        $.ajax({
                            type: "GET",
                            url: "{{ route('akun.edit_post') }}?id_post=" + id_post,
                            success: function(r) {
                                $("#load_edit_post_center").html(r);
                                $("#edit_post").modal('show')
                            }
                        });
                    })

                    $(document).on('submit', '#form_edit_post', function(e) {
                        e.preventDefault()
                        var datas = $(this).serialize()
                        $.ajax({
                            type: "GET",
                            url: "{{ route('akun.update_post_center') }}",
                            data: datas,
                            success: function(r) {
                                load_post_center(r)
                                $("#edit_post").modal('hide')

                            }
                        });
                    })

                    $(document).on('click', '.hapus_post', function(e) {
                        e.preventDefault()
                        var id_post = $(this).attr('id_post')
                        var id_akun = $(this).attr('id_akun')
                        if (confirm('Yakin dihapus ? ')) {
                            $.ajax({
                                type: "GET",
                                url: "{{ route('akun.delete_post_center') }}?id_post=" + id_post,
                                success: function(r) {
                                    load_post_center(id_akun)
                                }
                            });
                        }
                    })
                });
            </script>
        @endsection
    </x-slot>
</x-theme.app>
