function toast(pesan) {
    Toastify({
        text: pesan,
        duration: 3000,
        position: "center",
        style: {
            background: "#EAF7EE",
            color: "#7F8B8B",
        },
        close: true,
        avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png",
    }).showToast();
}

var hal = $(".halaman").val();
var search = $(".search").val();
var perpage = $("#perpage").val();

load_menu(hal, search, perpage);

function load_menu(page, search, perpage) {
    $.ajax({
        type: "get",
        url: "menu/get_menu",
        data: {
            page: page,
            search: search,
            perpage: perpage,
        },
        success: function (response) {
            $("#load_menu").html(response);
        },
        error: function (error) {
            console.error("Error fetching menu:", error);
        },
    });
}
$("body").on("click", ".pagination a", function (e) {
    e.preventDefault();

    var page = $(this).attr("href").split("page=")[1];
    var search = $(".search").val();
    var perpage = $("#perpage").val();
    var halaman = $(".halaman").val(page);

    load_menu(page, search, perpage);
});

$(document).on("keyup", ".search", function () {
    search = $(this).val();
    var hal = $(".halaman").val();
    var perpage = $("#perpage").val();
    load_menu(hal, search, perpage);
});
$(document).on("change", ".perpage", function () {
    var perpage = $(this).val();

    var perpage2 = $("#perpage").val(perpage);

    load_menu(hal, search, perpage);
});

$(document).on("change", ".chekstatus", function () {
    var isChecked = $(this).prop("checked");
    var id_menu = $(this).attr("id_menu");

    // Kirim permintaan AJAX
    $.ajax({
        url: "menu/aktif", // Ganti dengan URL yang sesuai
        type: "GET",
        data: {
            status: isChecked ? "on" : "off",
            id_menu: id_menu,
        },
        success: function (response) {
            toast("Menu berhasil di update");
        },
        error: function (error) {
            console.error(error);
        },
    });
});

var count = 1;
$(document).on("click", ".tambah_baris_resep", function () {
    count = count + 1;

    $.ajax({
        type: "get",
        url: "menu/tambah_baris_resep",
        data: {
            count: count,
        },
        success: function (response) {
            $(".load_tambah_resep").append(response);
            $(".select_resep").select2();
        },
    });
});
$(document).on("click", ".remove_baris", function () {
    var count = $(this).attr("count");
    $(".baris" + count).remove();
});
$(document).on("change", ".id_bahan", function () {
    var count = $(this).attr("count");
    var id_bahan = $(this).val();
    $.ajax({
        type: "get",
        url: "menu/get_satuan_resep",
        data: {
            id_bahan: id_bahan,
        },
        success: function (response) {
            $(".nm_bahan" + count).val(response);
        },
    });
});

$(document).on("submit", "#save_menu", function (e) {
    e.preventDefault(); // Mencegah pengiriman formulir secara tradisional

    // Mengambil nilai token CSRF dari tag meta
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    // Menambahkan token CSRF ke dalam FormData
    var formData = new FormData(this);
    formData.append("_token", csrfToken);

    // Menggunakan AJAX untuk mengirim data ke server
    $.ajax({
        type: "POST",
        url: "menu/save_menu", // Sesuaikan dengan URL yang sesuai
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            toast("Menu berhasil di update");
            $("#tambah").modal("hide");
            $("#save_menu")[0].reset();

            load_menu(1, search, perpage);
        },
        error: function (error) {
            console.log(error);
            // Toastify({
            //     text: "Data gagal disimpan" +,
            //     duration: 3000,
            //     style: {
            //         background: "#FCEDE9",
            //         color: "#7F8B8B",
            //     },
            //     close: true,
            //     avatar: "https://cdn-icons-png.flaticon.com/512/564/564619.png",
            // }).showToast();
        },
    });
});

$(document).on("click", ".delete_menu", function () {
    var id_menu = $(this).attr("id_menu");
    $(".id_menu").val(id_menu);
});

$(document).on("submit", "#delete_menu", function (e) {
    e.preventDefault(); // Prevent the default form submission
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var url = "menu/delete_menu";
    $.ajax({
        type: "POST",
        url: url,
        data: $(this).serialize(),
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            // Handle success, for example, close the modal
            toast("Menu berhasil di hapus");
            $("#delete").modal("hide");
            load_menu(hal, search, perpage);
            // You can also update the UI or perform any other action as needed
        },
        error: function (error) {
            // Handle error, for example, show an alert
            alert("Error: " + error.responseText);
        },
    });
});

$(document).on("click", ".edit_menu", function () {
    var id_menu = $(this).attr("id_menu");
    $.ajax({
        type: "GET",
        url: "menu/get_edit",
        data: {
            id_menu: id_menu,
        },
        success: function (response) {
            $("#load_edit").html(response);
            $(".selectedit").select2({
                dropdownParent: $("#edit .modal-content"),
            });
            $(".dropify").dropify({
                messages: {
                    default: "Drag",
                    replace: "Ganti",
                    remove: "Hapus",
                    error: "error",
                },
            });
        },
    });
});

$(document).on("submit", "#edit_menu", function (e) {
    e.preventDefault(); // Mencegah pengiriman formulir secara tradisional

    // Mengambil nilai token CSRF dari tag meta
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    // Menambahkan token CSRF ke dalam FormData
    var formData = new FormData(this);
    formData.append("_token", csrfToken);

    // Menggunakan AJAX untuk mengirim data ke server
    $.ajax({
        type: "POST",
        url: "menu/edit", // Sesuaikan dengan URL yang sesuai
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            toast("Menu berhasil di update");
            $("#edit").modal("hide");
            $("#edit_menu")[0].reset();
            var halaman = $(".halaman").val();
            load_menu(halaman, search, perpage);
        },
        error: function (error) {
            Toastify({
                text: "Data gagal disimpan",
                duration: 3000,
                style: {
                    background: "#FCEDE9",
                    color: "#7F8B8B",
                },
                close: true,
                avatar: "https://cdn-icons-png.flaticon.com/512/564/564619.png",
            }).showToast();
        },
    });
});

$(document).on("click", ".resep", function () {
    var id_menu = $(this).attr("id_menu");
    $.ajax({
        type: "get",
        url: "menu/get_resep",
        data: {
            id_menu: id_menu,
        },
        success: function (response) {
            $("#load_resep").html(response);
            $(".select_edit_resep").select2();
        },
    });
});

$(document).on("submit", "#edit_resep", function (e) {
    e.preventDefault(); // Mencegah pengiriman formulir secara tradisional

    // Mengambil nilai token CSRF dari tag meta
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    // Menambahkan token CSRF ke dalam FormData
    var formData = new FormData(this);
    formData.append("_token", csrfToken);

    // Menggunakan AJAX untuk mengirim data ke server
    $.ajax({
        type: "POST",
        url: "/menu/save_resep", // Sesuaikan dengan URL yang sesuai
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            toast("Resep berhasil di update");
            $("#resep").modal("hide");
            $("#edit_resep")[0].reset();

            load_menu(hal, search, perpage);
        },
        error: function (error) {
            Toastify({
                text: "Data gagal disimpan",
                duration: 3000,
                style: {
                    background: "#FCEDE9",
                    color: "#7F8B8B",
                },
                close: true,
                avatar: "https://cdn-icons-png.flaticon.com/512/564/564619.png",
            }).showToast();
        },
    });
});
