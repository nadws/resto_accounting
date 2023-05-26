<x-theme.app title="{{ $title }}" table="Y" sizeCard="10">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <h6 class="float-start mt-1">{{$title}}</h6>
            </div>
            <div class="col-lg-6">
                <a data-bs-toggle="modal" data-bs-target="#delete" href="#" class="btn btn-primary btn-sm float-end"><i
                        class="fas fa-window-close"></i> Penutup</a>
                <a data-bs-toggle="modal" data-bs-target="#history" href="#"
                    class="btn btn-primary btn-sm float-end me-2 history"><i class="fas fa-history"></i> History</a>
            </div>
        </div>
    </x-slot>

    <x-slot name="cardBody">

        <section class="row">
            <div class="alert alert-danger">
                <i class="bi bi-file-excel"></i> Saldo <b><em>{{ tanggal($tgl1Tutup) }} ~ {{ tanggal($tgl2Tutup)
                        }}</em></b>
                Belum Di Tutup.
            </div>
            <style>
                .dhead {
                    background-color: #435EBE !important;
                    color: white;
                }
            </style>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        {{-- <th class="dhead" width="5">#</th> --}}
                        <th class="dhead">Kode Akun</th>
                        <th class="dhead">Akun</th>
                        <th class="dhead" style="text-align: right">Debit</th>
                        <th class="dhead" style="text-align: right">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" class="fw-bold">Pendapatan</td>
                    </tr>
                    @foreach ($pendapatan as $no => $b)
                    <tr>
                        <td>{{$b->kode_akun}}</td>
                        <td>{{ ucwords(strtolower($b->nm_akun))}}</td>
                        <td align="right">{{number_format($b->kredit,2)}}</td>
                        <td align="right">0</td>
                    </tr>
                    <tr>
                        <td>5004</td>
                        <td>Ikhtisar Laba Rugi</td>
                        <td align="right">0</td>
                        <td align="right">{{number_format($b->kredit,2)}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="fw-bold">Biaya</td>
                    </tr>
                    @foreach ($biaya as $no => $b)
                    <tr>
                        <td>{{$b->kode_akun}}</td>
                        <td>{{ ucwords(strtolower($b->nm_akun))}}</td>
                        <td align="right">0</td>
                        <td align="right">{{number_format($b->debit,2)}}</td>
                    </tr>
                    <tr>
                        <td>5004</td>
                        <td>Ikhtisar Laba Rugi</td>
                        <td align="right">{{number_format($b->debit,2)}}</td>
                        <td align="right">0</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </section>

        <form action="" method="get">
            <x-theme.modal title="Filter Buku" idModal="view">
                <div class="row">
                    <div class="col-lg-12">
                        <table width="100%" cellpadding="10px">
                            <tr>
                                <td><label for="">&nbsp;</label> <br>Tanggal</td>
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


        <form action="{{ route('penutup.saldo') }}" method="get">
            <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h5 class="text-danger ms-4 mt-4"><i class="fas fa-window-close"></i> Saldo Penutup</h5>
                                <p class=" ms-4 mt-4">Apa anda yakin ingin menutup saldo ?</p>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- history --}}
        <form action="" method="get">
            <x-theme.modal title="History Penutup" size="modal-lg" btnSave="" idModal="history">
                <div id="load-history"></div>
            </x-theme.modal>
        </form>
        {{-- end history --}}
    </x-slot>
    @section('scripts')
    <script>
        // Menangani event klik pada setiap baris dan mengarahkan pengguna ke URL yang sesuai
            document.querySelectorAll('tbody .tbl').forEach(function(row) {
                row.addEventListener('click', function() {
                    window.location.href = row.getAttribute('data-href');
                });
            });

            $(document).on('click', '.history', function() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('penutup.history') }}",
                    success: function(r) {
                        $("#load-history").html(r);
                    }
                });
            })
    </script>
    @endsection

</x-theme.app>