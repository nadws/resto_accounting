$(document).ready(function () {
    $(document).on("keyup", ".b_estimasi", function () {
        var rupiah = $(this)
                .val()
                .replace(/[^,\d]/g, "")
                .toString(),
            split = rupiah.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        $(this).val("Rp " + rupiah);
        var rupiah_biasa = parseFloat(rupiah.replace(/[^\d]/g, ""));
        $(".b_estimasi_biasa").val(rupiah_biasa);
    });
    $(".delete_proyek").click(function () {
        var id_proyek = $(this).attr("id_proyek");
        $(".id_proyek").val(id_proyek);
    });
    $(".selesai_proyek").click(function () {
        var id_proyek = $(this).attr("id_proyek");
        $(".id_proyek_selesai").val(id_proyek);
    });
});
