$(document).ready(function () {
    load_menu();
    function load_menu() {
        $.ajax({
            method: "GET",
            url: "/load_menu",
            dataType: "html",
            success: function (hasil) {
                $("#load_menu").html(hasil);
                $(".select").select2();
            },
        });
    }

    $(document).on("click", ".remove_baris", function () {
        var delete_row = $(this).attr("count");
        $(".baris" + delete_row).remove();

        var total_debit = 0;
        $(".debit_biasa").each(function () {
            total_debit += parseFloat($(this).val());
        });
        var totalRupiah_debit = total_debit.toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
        });
        var debit = $(".total").text(totalRupiah_debit);

        var total_kredit = 0;
        $(".kredit_biasa").each(function () {
            total_kredit += parseFloat($(this).val());
        });
        var totalRupiah = total_kredit.toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
        });
        $(".total_kredit").text(totalRupiah);

        var selisih = total_debit - total_kredit;
        var selisih_total = selisih.toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
        });

        if (selisih === 0) {
            $(".cselisih").css("color", "green");
            $(".button-save").removeAttr("hidden");
        } else {
            $(".cselisih").css("color", "red");
            $(".button-save").attr("hidden", true);
        }
        $(".selisih").text(selisih_total);
    });
    var count = 3;
    $(document).on("click", ".tbh_baris", function () {
        count = count + 1;
        $.ajax({
            url: "/tambah_baris_jurnal?count=" + count,
            type: "Get",
            success: function (data) {
                $("#tb_baris").append(data);
                $(".select").select2();
            },
        });
    });
    
    $(document).on("keyup", ".debit_rupiah", function () {
        var count = $(this).attr("count");
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

        var debit = 0;
        $(".debit_rupiah").each(function () {
            debit += parseFloat($(this).val());
        });

        if (rupiah === "") {
            $(this).val("Rp 0");
            $(".debit_biasa" + count).val("0");
        } else {
            $(this).val("Rp " + rupiah);
            var rupiah_biasa = parseFloat(rupiah.replace(/[^\d]/g, ""));
            $(".debit_biasa" + count).val(rupiah_biasa);
        }
        var total_debit = 0;
        $(".debit_biasa").each(function () {
            total_debit += parseFloat($(this).val());
        });

        var totalRupiah = total_debit.toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
        });
        var debit = $(".total").text(totalRupiah);

        var total_kredit = 0;
        $(".kredit_biasa").each(function () {
            total_kredit += parseFloat($(this).val());
        });
        var selisih = total_debit - total_kredit;
        var selisih_total = selisih.toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
        });
        if (selisih === 0) {
            $(".cselisih").css("color", "green");
            $(".button-save").removeAttr("hidden");
        } else {
            $(".cselisih").css("color", "red");
            $(".button-save").attr("hidden", true);
        }
        $(".selisih").text(selisih_total);
    });

    $(document).on("keyup", ".kredit_rupiah", function () {
        var count = $(this).attr("count");
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
        if (rupiah === "") {
            $(this).val("Rp 0");
        } else {
            $(this).val("Rp " + rupiah);

            var rupiah_biasa = parseFloat(rupiah.replace(/[^\d]/g, ""));
            $(".kredit_biasa" + count).val(rupiah_biasa);
        }
        var total_debit = 0;
        $(".debit_biasa").each(function () {
            total_debit += parseFloat($(this).val());
        });

        var total_kredit = 0;
        $(".kredit_biasa").each(function () {
            total_kredit += parseFloat($(this).val());
        });
        var totalRupiah = total_kredit.toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
        });
        $(".total_kredit").text(totalRupiah);

        var selisih = total_debit - total_kredit;
        var selisih_total = selisih.toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
        });

        if (selisih === 0) {
            $(".cselisih").css("color", "green");
            $(".button-save").removeAttr("hidden");
        } else {
            $(".cselisih").css("color", "red");
            $(".button-save").attr("hidden", true);
        }
        $(".selisih").text(selisih_total);
    });

    $("form").on("keypress", function (e) {
        if (e.which === 13) {
            e.preventDefault();
            return false;
        }
    });

    $(".pilihan_l").hide();

    $(document).on("click", "#Pilihan_Lainnya", function () {
        if ($(this).prop("checked") == true) {
            $(".pilihan_l").show();
            $(".inp-lain").removeAttr("disabled");
        } else if ($(this).prop("checked") == false) {
            $(".pilihan_l").hide();
        }
    });

    aksiBtn("form");
});
