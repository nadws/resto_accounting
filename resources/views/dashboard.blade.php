<x-theme.app title="{{ $title }}" table="Y" sizeCard="8">
    <x-slot name="cardHeader">
    </x-slot>
    <x-slot name="cardBody">
        <h5 class="mb-3">Tambah Cashflow</h5>
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Tanggal</label>
                    <input type="date" value="{{ date('Y-m-d') }}" id="tgl" class="form-control">
                </div>
            </div>
            <div class="col-lg-5">
                <div class="form-group">
                    <label for="">Keterangan</label>
                    <input type="text" id="ket" class="form-control">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="">Uang</label><br>
                    <input type="radio" class="btn-check pilihan" pilihan="uangKeluar" name="options-outlined"
                        id="secondary-outlined" autocomplete="off" checked="">
                    <label class="btn btn-outline-secondary" for="secondary-outlined">Keluar</label>

                    <input type="radio" class="btn-check pilihan" pilihan="uangMasuk" name="options-outlined"
                        id="primary-outlined" autocomplete="off">
                    <label class="btn btn-outline-primary" for="primary-outlined">Masuk</label>
                </div>
            </div>

        </div>
        <div class="row">
            @php
                $duit = [50, 100, 200, 300, 500, 1];
            @endphp
            @foreach ($duit as $i => $d)
                <div class="col-lg-6 col-6 mb-2">
                    <button
                        class="btn rounded-pill btn-outline-primary btn-block btnNominal btnNominal{{ $i + 1 }}"
                        nominal="{{ $d == 1 ? 1000 : $d }}" count="{{ $i + 1 }}">
                        <span style="font-size: 25px">{{ $d }}</span> {{ $d == 1 ? 'juta' : 'ribu' }}
                    </button>
                </div>
            @endforeach

            <div class="col-lg-12 col-12 mt-3">
                <label for="angka">Nominal</label>
                <input type="hidden" value="uangKeluar" id="setPilihan">
                <form id="submitUang">
                    <input id="angka" oninput="formatAngka(this)"
                        style="font-size:30px;height: 50px; font-weight: bold" type="text" placeholder="Rp. "
                        class="form-control">
                    <button disabled type="submit" id="btnLanjutkan"
                        class="mt-3 btn btn-block btn-primary">Lanjutkan</button>

                    <button id="btnLoading" class="mt-3 btn btn-block btn-primary" type="button" disabled="">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>

                </form>
            </div>
        </div>





        @section('scripts')
            <script>
                function formatAngka(input) {
                    // Menghilangkan semua karakter selain angka dari input
                    let angka = input.value.replace(/\D/g, '');
                    input.value = angka;
                    // Mengecek apakah input tidak kosong dan merupakan angka
                    if (angka !== '' && !isNaN(angka)) {
                        // Mengubah angka menjadi format dengan ribuan terpisah koma
                        let formattedAngka = new Intl.NumberFormat('id-ID').format(angka);

                        // Memisahkan ribuan terpisah koma setiap 3 digit, juga memastikan tidak ada dua koma berdampingan
                        let parts = formattedAngka.split(',');
                        formattedAngka = parts.join(',').replace(/,([^,]*)$/, ',$1');

                        // Memasukkan kembali angka ke dalam input dengan format
                        input.value = formattedAngka;
                    }
                }
                $(document).on('click', '.pilihan', function() {
                    var pilihan = $(this).attr('pilihan')
                    $("#setPilihan").val(pilihan);
                })
                $(document).on('click', '.btnNominal', function() {
                    $("#btnLanjutkan").removeAttr('disabled');
                    var count = $(this).attr('count')
                    var nominal = $(this).attr('nominal')
                    angka = parseFloat(nominal) * 1000

                    let formattedAngka = new Intl.NumberFormat('id-ID').format(angka);

                    let parts = formattedAngka.split(',');
                    formattedAngka = parts.join(',').replace(/,([^,]*)$/, ',$1');

                    nominal = formattedAngka;

                    $("#angka").val(nominal);
                })

                $(document).on('keyup', '#angka', function() {
                    var angka = $(this).val()
                    if (angka !== '') {
                        $("#btnLanjutkan").removeAttr('disabled');
                    } else {
                        $("#btnLanjutkan").attr('disabled', true);
                    }

                })

                $('#btnLoading').hide()
                $('#btnLanjutkan').show()

                $(document).on('submit', '#submitUang', function(e) {
                    e.preventDefault();
                    let angka = $('#angka').val();
                    let pilihan = $("#setPilihan").val()
                    let tgl = $("#tgl").val()
                    let ket = $("#ket").val()

                    $.ajax({
                        type: "GET",
                        url: "{{ route('cashflow.keluar') }}",
                        data: {
                            nominal: angka,
                            pilihan: pilihan,
                            tgl: tgl,
                            ket: ket,
                        },
                        beforeSend: function() {
                            $('#btnLoading').show()
                            $('#btnLanjutkan').hide()
                        },
                        success: function(r) {
                            $('#btnLoading').hide()
                            $('#btnLanjutkan').show()
                            $('#angka').val('')

                            Toastify({
                                text: "Data Berhasil di masukkan",
                                duration: 5000,
                                style: {
                                    background: "#EAF7EE",
                                    color: "#7F8B8B"
                                },
                                close: true,
                                position: 'center',
                                avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                            }).showToast();
                        }
                    });
                })
            </script>
        @endsection
    </x-slot>
</x-theme.app>
