$(document).on("change", ".pilih_akun", function () {
    var count = $(this).attr("count");
    var id_akun = $(".pilih_akun" + count).val();
    var kredit_biasa = $(".kredit_biasa" + count).val();
    var debit_biasa = $(".debit_biasa" + count).val();

    $.ajax({
        url: "/saldo_akun?id_akun=" + id_akun,
        type: "Get",
        dataType: "json",
        success: function (data) {
            var id_klasifikasi = $(".id_klasifikasi" + count).val(
                data["id_klasifikasi"]
            );

            $(".nilai" + count).val(data["nilai"]);
            $(".saldo" + count).val(data["saldo"]);
            var nilai = data["nilai"];

            // if (nilai == 1) {
            //     $('.peringatan_akun' + count).attr("hidden", false);
            // } else {
            //     $('.peringatan_akun' + count).attr("hidden", true);
            // }

            var total_nilai = 0;
            $(".nilai").each(function () {
                total_nilai += parseFloat($(this).val());
            });

            if (total_nilai > 0) {
                $(".button-save").prop("disabled", true);
            } else {
                $(".button-save").prop("disabled", false);
            }

            if (nilai != 1) {
                $(".peringatan_akun" + count).attr("hidden", true);
            } else {
                $(".peringatan_akun" + count).attr("hidden", false);
                setTimeout(function () {
                    $(".peringatan_akun" + count).removeClass("vibrate");
                }, 1000);
            }

            if (id_klasifikasi == 3) {
                if (kredit_biasa != "0") {
                    $(".peringatan" + count).attr("hidden", false);
                } else {
                    $(".peringatan" + count).attr("hidden", true);
                }
            } else {
                $(".peringatan" + count).attr("hidden", true);
            }
            if (id_klasifikasi == 1) {
                if (debit_biasa != "0") {
                    $(".peringatan" + count).attr("hidden", false);
                } else {
                    $(".peringatan" + count).attr("hidden", true);
                }
            } else {
                $(".peringatan" + count).attr("hidden", true);
            }
        },
    });
});
$(document).on("change", ".pilih_akun", function () {
    var count = $(this).attr("count");
    var id_akun = $(".pilih_akun" + count).val();
    $.ajax({
        url: "/get_post?id_akun=" + id_akun,
        type: "Get",
        success: function (data) {
            $(".post" + count).html(data);
        },
    });
});
