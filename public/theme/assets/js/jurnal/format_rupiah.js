$(document).on("keyup", ".debit_rupiah", function () {
    var count = $(this).attr("count");
    var id_klasifikasi = $(".id_klasifikasi" + count).val();
    var saldo = $(".saldo" + count).val();

    var input = $(this).val();
    input = input.replace(/[^\d\,]/g, "");
    input = input.replace(".", ",");
    input = input.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

    if (input === "") {
        $(this).val("");
        $(".debit_biasa" + count).val(0);
    } else {
        $(this).val("Rp " + input);
        input = input.replaceAll(".", "");
        input2 = input.replace(",", ".");
        $(".debit_biasa" + count).val(input2);
    }

    if (id_klasifikasi === "1" || id_klasifikasi === "2") {
        $(".peringatan_debit" + count).attr("hidden", false);
    } else {
        $(".peringatan_debit" + count).attr("hidden", true);
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

function number_format(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$(document).on("keyup", ".kredit_rupiah", function () {
    var count = $(this).attr("count");
    var input = $(this).val();
    var id_klasifikasi = $(".id_klasifikasi" + count).val();
    input = input.replace(/[^\d\,]/g, "");
    input = input.replace(".", ",");
    input = input.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");

    if (input === "") {
        $(this).val("");
        $(".kredit_biasa" + count).val(0);
    } else {
        $(this).val("Rp " + input);
        input = input.replaceAll(".", "");
        input2 = input.replace(",", ".");
        $(".kredit_biasa" + count).val(input2);
    }
    var saldo = $(".saldo" + count).val();

    if (id_klasifikasi === "2") {
        $(".peringatan" + count).attr("hidden", false);
    } else {
        $(".peringatan" + count).attr("hidden", true);
    }

    if (
        id_klasifikasi === "5" ||
        id_klasifikasi === "6" ||
        id_klasifikasi === "7"
    ) {
        if (parseFloat(saldo) - input2 < 0) {
            $(".alert_saldo").attr("hidden", true);
            $(".peringatan_saldo" + count)
                .removeAttr("hidden")
                .text("Saldo saat ini = " + number_format(saldo));
        } else {
            $(".alert_saldo").attr("hidden", false);
            $(".peringatan_saldo" + count).attr("hidden", true);
        }
    }

    var total_kredit = 0;
    $(".kredit_biasa").each(function () {
        total_kredit += parseFloat($(this).val());
    });

    var totalRupiah = total_kredit.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
    });
    var debit = $(".total_kredit").text(totalRupiah);

    var total_debit = 0;
    $(".debit_biasa").each(function () {
        total_debit += parseFloat($(this).val());
    });
    var selisih = total_debit - total_kredit;
    var selisih_total = selisih.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
    });
    if (total_debit === total_kredit) {
        $(".cselisih").css("color", "green");
        $(".button-save").removeAttr("hidden");
    } else {
        $(".cselisih").css("color", "red");
        $(".button-save").attr("hidden", true);
    }
    $(".selisih").text(selisih_total);
});
