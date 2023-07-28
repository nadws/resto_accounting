<footer>
    <div class="container">
        <div class="footer clearfix mb-0 text-sm text-muted">
            <div class="float-start">
                <p>2023 &copy; PTAGAFOOD</p>
            </div>
            <div class="float-end">
                <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                        href="https://ptagafood.com">AgrikaGroup</a></p>
            </div>
        </div>
    </div>
</footer>
</div>
</div>
<script src="{{ asset('theme') }}/assets/js/bootstrap.js"></script>
<script src="{{ asset('theme') }}/assets/js/app.js"></script>
<script src="{{ asset('theme') }}/assets/extensions/jquery/jquery.min.js"></script>

<script src="{{ asset('theme') }}/assets/js/pages/form-element-select.js"></script>
<script src="{{ asset('theme') }}/assets/extensions/toastify-js/src/toastify.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="{{ asset('theme') }}/assets/js/pages/datatables.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('theme') }}/assets/js/pages/horizontal-layout.js"></script>
<script src="{{ asset('theme') }}/assets/extensions/dragula/dragula.min.js"></script>
<script src="{{ asset('theme') }}/assets/js/pages/ui-todolist.js"></script>
<script>
    $('.select2').select2({
        dropdownParent: $('#tambah .modal-content')
    });
    $('.select2-tambah2').select2({
        dropdownParent: $('#tambah2 .modal-content')
    });
    $('#selectView').select2({
        dropdownParent: $('#view .modal-content')
    });
    $('.selectView').select2({
        dropdownParent: $('#view .modal-content')
    });
    $('.costume_muncul').hide();
    $('.bulan_muncul').hide();
    $('.tahun_muncul').hide();
    $('.tgl').prop('disabled', true);
    $(document).on("change", ".filter_tgl", function() {
        var period = $(this).val();
        $('.costume_muncul').toggle(period === 'costume');
        $('.tgl').prop('disabled', period !== 'costume');

        $('.bulan_muncul').toggle(period === 'mounthly');
        $('.bulan').prop('disabled', period !== 'mounthly');

        $('.tahun_muncul').toggle(period === 'years');
        $('.tahun').prop('disabled', period !== 'years');


    });
    $('#select2').select2({});
    $('.select2_add').select2({});
    $('.select2_readonly').select2({
        disabled: true
    });
</script>
@if (session()->has('sukses'))
    <script>
        $(document).ready(function() {
            Toastify({
                text: "{{ session()->get('sukses') }}",
                duration: 3000,
                style: {
                    background: "#EAF7EE",
                    color: "#7F8B8B"
                },
                close: true,
                avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
            }).showToast();
        });
    </script>
@endif
@if (session()->has('error'))
    <script>
        $(document).ready(function() {
            Toastify({
                text: "{{ session()->get('error') }}",
                duration: 3000,
                style: {
                    background: "#FCEDE9",
                    color: "#7F8B8B"
                },
                close: true,
                avatar: "https://cdn-icons-png.flaticon.com/512/564/564619.png"
            }).showToast();


        });
    </script>
@endif
@yield('scripts')

</body>

</html>
