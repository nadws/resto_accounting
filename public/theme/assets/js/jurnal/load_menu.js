load_menu();

function load_menu() {
    var urlParams = new URLSearchParams(window.location.search);
    var id_akun = urlParams.get("id_akun");
    var id_buku = urlParams.get("id_buku");

    if (id_akun) {
        $.ajax({
            method: "GET",
            url: "load_menu",
            dataType: "html",
            data: {
                id_akun: id_akun,
                id_buku: id_buku,
            },
            success: function (hasil) {
                $("#load_menu").html(hasil);
                $(".select").select2({
                    language: {
                        searching: function () {
                            $(".select2-search__field").focus();
                        },
                    },
                });
            },
        });
    } else {
        var defaultIdAkun = "default_value";
        $.ajax({
            method: "GET",
            url: "load_menu",
            dataType: "html",
            data: {
                id_akun: defaultIdAkun,
                id_buku: id_buku,
            },
            success: function (hasil) {
                $("#load_menu").html(hasil);
                $(".select").select2({
                    language: {
                        searching: function () {
                            $(".select2-search__field").focus();
                        },
                    },
                });
            },
        });
    }

    var count = 3;
    $(document).on("click", ".tbh_baris", function () {
        count = count + 1;
        $.ajax({
            url: "tambah_baris_jurnal",
            type: "Get",
            data: {
                count: count,
            },
            success: function (data) {
                $("#tb_baris").append(data);
                $(".select").select2();
            },
        });
    });

    $(document).on("click", ".remove_baris", function () {
        var delete_row = $(this).attr("count");
        $(".baris" + delete_row).remove();
    });

    $(document).on("change", ".pilih_akun", function() {
        var count = $(this).attr("count");
        var id_akun = $(".pilih_akun" + count).val();
        $.ajax({
            url: "get_post",
            type: "GET",
            data:{
                id_akun:id_akun
            },
            success: function(data) {
                $(".post" + count).html(data);
            },
        });
    });
}
