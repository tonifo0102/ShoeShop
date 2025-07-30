$(document).ready(function () {
    const baseURL = $("base#baseURL").attr("href");

    $(".slide").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: false,
        dots: false,
    });

    $(document).on("click", "#btn-login", function (event) {
        event.preventDefault();

        const username = $("#username").val();
        const password = $("#password").val();

        if (username == "" || password == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "login",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                username: username,
                password: password,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                    timer: 3000,
                }).then(function () {
                    location.reload();
                });
            },
        });
    });

    $(document).on("click", "#btn-register", function (event) {
        event.preventDefault();

        const username = $("#username").val();
        const password = $("#password").val();
        const confirm_password = $("#confirm-password").val();
        const name = $("#name").val();
        const phone = $("#phone").val();

        if (
            username == "" ||
            password == "" ||
            confirm_password == "" ||
            name == "" ||
            phone == ""
        ) {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        if (password != confirm_password) {
            swal({
                title: "Thông báo",
                text: "Mật khẩu không khớp",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "register",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                username: username,
                password: password,
                name: name,
                phone: phone,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                    timer: 3000,
                }).then(function () {
                    window.location.href = baseURL + "login";
                });
            },
        });
    });

    $(document).on("click", "#btn-forgot-password", function (event) {
        event.preventDefault();

        const username = $("#username").val();
        const phone = $("#phone").val();

        if (username == "" || phone == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "forgot-password",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                username: username,
                phone: phone,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    window.location.href = "/login";
                });
            },
        });
    });

    $(document).on("click", "#btn-update-info", function (event) {
        event.preventDefault();

        const name = $("#name").val();
        const phone = $("#phone").val();
        const address = $("#address").val();

        if (name == "" || phone == "" || address == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "dashboard/info/update",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                name: name,
                phone: phone,
                address: address,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {});
            },
        });
    });

    $(document).on("click", "#btn-change-password", function (event) {
        event.preventDefault();

        const old_password = $("#password-old").val();
        const new_password = $("#password-new").val();
        const confirm_password = $("#password-confirm").val();

        if (
            old_password == "" ||
            new_password == "" ||
            confirm_password == ""
        ) {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        if (new_password != confirm_password) {
            swal({
                title: "Thông báo",
                text: "Mật khẩu không khớp",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "dashboard/change-password/update",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                old_password: old_password,
                new_password: new_password,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    $("#password-old").val("");
                    $("#password-new").val("");
                    $("#password-confirm").val("");
                });
            },
        });
    });

    $("table.table-datatables").DataTable({
        language: {
            info: "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ mục",
            lengthMenu: "Hiển thị _MENU_ mục",
            infoFiltered: "(Tìm kiếm từ _MAX_)",
            infoEmpty: "Hiển thị từ 0 đến 0 trong tổng số 0 mục",
            search: "Tìm kiếm",
            paginate: {
                first: "Đầu tiên",
                last: "Cuối cùng",
                next: "Tiếp",
                previous: "Trước",
            },
            zeroRecords: "Không tìm thấy kết quả",
        },
        columnDefs: [
            {
                targets: -1,
                searchable: false,
                orderable: false,
            }
        ],
        responsive: true
    });

    $("select").select2();

    $(document).on("click", "#btn-locked", function () {
        const id = parseInt($(this).attr("data-id"));
        const status = parseInt($(this).attr("data-status"));
        openLoading();

        $.ajax({
            url: baseURL + "admin/account-manager/locked",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                id: id,
                status: status,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    $(`#btn-locked[data-id="${id}"]`).html(
                        status ? "Đang kích hoạt" : "Đã khóa"
                    );
                    $(`#btn-locked[data-id="${id}"]`).attr(
                        "data-status",
                        status ? "0" : "1"
                    );
                });
            },
        });
    });

    $(document).on("click", "#close-modal", function () {
        closeModal();
    });

    $(document).on("click", "#btn-open-modal", function () {
        const modal = $(this).attr("data-modal");
        openModal(modal);
    });

    function openModal(modal) {
        $(`#${modal}`).removeClass("hide");
    }

    function closeModal() {
        $(".modal").addClass("hide");
    }

    function clearModal(modal) {
        $(`#${modal} input`).val("");
        $(`#${modal} textarea`).val("");
        $(`#${modal} input[type="file"]`).val("");
        $(`#${modal} .preview-image`).html(
            '<i class="fa fa-image"></i></label>'
        );
    }

    function openLoading() {
        $(".loading").removeClass("hide");
    }

    function closeLoading() {
        $(".loading").addClass("hide");
    }

    $(document).on("click", "#btn-create-account-shipper", function () {
        const name = $("#name").val();
        const phone = $("#phone").val();
        const username = $("#username").val();
        const password = $("#password").val();

        if (name == "" || phone == "" || username == "" || password == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "admin/account-manager/create-shipper",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                name: name,
                phone: phone,
                username: username,
                password: password,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    const html = `
                        <tr>
                            <td>${response.data.id}</td>
                            <td>${response.data.username}</td>
                            <td>${response.data.name}</td>
                            <td>${response.data.phone}</td>
                            <td></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" id="btn-locked" data-id="${response.data.id}" data-status="0">Đang kích hoạt</button>
                            </td>
                        </tr>
                    `;
                    $("#account-shipper").DataTable().row.add($(html)).draw();

                    $("#name").val("");
                    $("#phone").val("");
                    $("#username").val("");
                    $("#password").val("");
                    closeModal();
                    clearModal("modal-create-account-shipper");
                });
            },
        });
    });

    $(document).on("click", "#btn-change-slug", function () {
        const action = $(this).attr("data-action");
        const category_name = $(this)
            .parents(".form")
            .find(`#${action + "category-name"}`)
            .val();

        const slug = category_name
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d")
            .replace(/Đ/g, "d")
            .replace(/[^a-z0-9]+/g, "-")
            .replace(/(^-|-$)/g, "");

        $(this)
            .parents(".form")
            .find(`#${action + "category-slug"}`)
            .val(slug);
    });

    $(document).on("click", "#btn-change-slug-product", function () {
        const action = $(this).attr("data-action");
        const product_name = $(this)
            .parents(".form")
            .find(`#${action + "product-name"}`)
            .val();

        const slug = product_name
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d")
            .replace(/Đ/g, "d")
            .replace(/[^a-z0-9]+/g, "-")
            .replace(/(^-|-$)/g, "");

        $(this)
            .parents(".form")
            .find(`#${action + "product-slug"}`)
            .val(slug);
    });

    $(document).on("click", "#btn-create-category", function () {
        const category_name = $("#category-name").val();
        const category_slug = $("#category-slug").val();

        if (category_name == "" || category_slug == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "admin/category-manager/create",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                name: category_name,
                slug: category_slug,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    const html = `
                        <tr>
                            <td>${response.data.id}</td>
                            <td>${response.data.name}</td>
                            <td>${response.data.slug}</td>
                            <td>0</td>
                            <td>
                                <button id="btn-update-category" class="btn btn-blue" data-id="${response.data.id}">Cập nhật</button>
                                <button id="btn-delete-category" class="btn btn-red" data-id="${response.data.id}">Xóa</button>
                            </td>
                        </tr>
                    `;
                    $("#category-manager").DataTable().row.add($(html)).draw();

                    $("#category-name").val("");
                    $("#category-slug").val("");
                    closeModal();
                    clearModal("modal-create-category");
                });
            },
        });
    });

    $(document).on("click", "#btn-delete-category", function () {
        const id = $(this).attr("data-id");

        swal({
            title: "Thông báo",
            text: "Bạn có chắc chắn muốn xóa danh mục này?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function (result) {
            if (result) {
                openLoading();
                $.ajax({
                    url: baseURL + "admin/category-manager/delete",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                    },
                    data: {
                        id: id,
                    },
                    success: function (response) {
                        closeLoading();
                        if (!response.status) {
                            swal({
                                title: "Thông báo",
                                text: response.message,
                                icon: "warning",
                                showCancelButton: false,
                            });
                            return;
                        }
                        swal({
                            title: "Thông báo",
                            text: response.message,
                            icon: "success",
                            showCancelButton: false,
                        }).then(function () {
                            $("#category-manager")
                                .DataTable()
                                .row(
                                    $(
                                        `#btn-delete-category[data-id="${id}"]`
                                    ).parents("tr")
                                )
                                .remove()
                                .draw();
                        });
                    },
                });
            }
        });
    });

    $(document).on("click", "#btn-update-category", function () {
        const id = $(this).attr("data-id");
        clearModal("modal-update-category");
        openLoading();

        $.ajax({
            url: baseURL + "admin/category-manager",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                id: id,
            },
            success: function (response) {
                closeLoading();

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                $("#update-category-name").val(response.data.name);
                $("#update-category-slug").val(response.data.slug);
                $("#update-category-id").val(response.data.id);
                openModal("modal-update-category");
            },
        });
    });

    $(document).on("click", "#btn-update-category-modal", function () {
        const id = $("#update-category-id").val();
        const name = $("#update-category-name").val();
        const slug = $("#update-category-slug").val();

        if (name == "" || slug == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "admin/category-manager/update",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                id: id,
                name: name,
                slug: slug,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    const newData = [
                        response.data.id,
                        response.data.name,
                        response.data.slug,
                        $("#category-manager")
                            .DataTable()
                            .row(
                                $(
                                    `#btn-update-category[data-id="${id}"]`
                                ).parents("tr")
                            )
                            .data()[3],
                        $("#category-manager")
                            .DataTable()
                            .row(
                                $(
                                    `#btn-update-category[data-id="${id}"]`
                                ).parents("tr")
                            )
                            .data()[4],
                    ];

                    $("#category-manager")
                        .DataTable()
                        .row(
                            $(`#btn-update-category[data-id="${id}"]`).parents(
                                "tr"
                            )
                        )
                        .data(newData)
                        .draw();
                    closeModal();
                    clearModal("modal-update-category");
                });
            },
        });
    });

    $(document).on("click", "#btn-create-discount", function () {
        const name = $("#name").val();
        const code = $("#code").val();
        const percent = $("#percent").val();
        const start_at = $("#start_at").val();
        const end_at = $("#end_at").val();

        if (
            name == "" ||
            code == "" ||
            percent == "" ||
            start_at == "" ||
            end_at == ""
        ) {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }
        if (percent < 0 || percent > 100) {
            swal({
                title: "Thông báo",
                text: "Phần trăm giảm giá phải từ 0 - 100",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }
        if (start_at >= end_at) {
            swal({
                title: "Thông báo",
                text: "Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "admin/discount-manager/create",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                name: name,
                code: code,
                percent: percent,
                start_at: start_at,
                end_at: end_at,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    const newData = [
                        response.data.id,
                        response.data.name,
                        response.data.code,
                        response.data.percent,
                        response.data.start_at,
                        response.data.end_at,
                        response.data.remaining,
                        `<button id="btn-update-discount" class="btn btn-blue" data-id="${response.data.id}">Cập nhật</button>
                        <button id="btn-delete-discount" class="btn btn-red" data-id="${response.data.id}">Xóa</button>`,
                    ];

                    $("#discount-manager").DataTable().row.add(newData).draw();
                    closeModal();
                    clearModal("modal-create-discount");
                });
            },
        });
    });

    $(document).on("click", "#btn-update-discount", function () {
        const id = $(this).attr("data-id");
        clearModal("modal-update-discount");

        openLoading();

        $.ajax({
            url: baseURL + "admin/discount-manager",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                id: id,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                $("#discount-name").val(response.data.discount.name);
                $("#discount-code").val(response.data.discount.code);
                $("#discount-percent").val(response.data.discount.percent);
                $("#discount-start_at").val(response.data.discount.start_at);
                $("#discount-end_at").val(response.data.discount.end_at);
                $("#discount-id").val(response.data.discount.id);
                openModal("modal-update-discount");
            },
        });
    });

    $(document).on("click", "#btn-update-discount-modal", function () {
        const id = $("#discount-id").val();
        const name = $("#discount-name").val();
        const code = $("#discount-code").val();
        const percent = $("#discount-percent").val();
        const start_at = $("#discount-start_at").val();
        const end_at = $("#discount-end_at").val();

        if (
            name == "" ||
            code == "" ||
            percent == "" ||
            start_at == "" ||
            end_at == ""
        ) {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }
        if (percent < 0 || percent > 100) {
            swal({
                title: "Thông báo",
                text: "Phần trăm giảm giá phải từ 0 - 100",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }
        if (start_at >= end_at) {
            swal({
                title: "Thông báo",
                text: "Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "admin/discount-manager/update",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            data: {
                id: id,
                name: name,
                code: code,
                percent: percent,
                start_at: start_at,
                end_at: end_at,
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    const newData = [
                        response.data.id,
                        response.data.name,
                        response.data.code,
                        response.data.percent,
                        response.data.start_at,
                        response.data.end_at,
                        response.data.remaining,
                        `<button id="btn-update-discount" class="btn btn-blue" data-id="${response.data.id}">Cập nhật</button>
                        <button id="btn-delete-discount" class="btn btn-red" data-id="${response.data.id}">Xóa</button>`,
                    ];

                    $("#discount-manager")
                        .DataTable()
                        .row(
                            $(
                                `#btn-update-discount[data-id="${response.data.id}"]`
                            ).parents("tr")
                        )
                        .data(newData)
                        .draw();
                    closeModal();
                    clearModal("modal-update-discount");
                });
            },
        });
    });

    $(document).on("click", "#btn-delete-discount", function () {
        const id = $(this).attr("data-id");

        swal({
            title: "Thông báo",
            text: "Bạn có chắc chắn muốn xóa mã giảm giá này?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function (result) {
            if (result) {
                openLoading();

                $.ajax({
                    url: baseURL + "admin/discount-manager/delete",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                    },
                    data: {
                        id: id,
                    },
                    success: function (response) {
                        closeLoading();
                        if (!response.status) {
                            swal({
                                title: "Thông báo",
                                text: response.message,
                                icon: "warning",
                                showCancelButton: false,
                            });
                            return;
                        }
                        swal({
                            title: "Thông báo",
                            text: response.message,
                            icon: "success",
                            showCancelButton: false,
                        }).then(function () {
                            $("#discount-manager")
                                .DataTable()
                                .row(
                                    $(
                                        `#btn-delete-discount[data-id="${id}"]`
                                    ).parents("tr")
                                )
                                .remove()
                                .draw();
                        });
                    },
                });
            }
        });
    });

    $(document).on("change", "#image", function () {
        if ($(this).val() == null || $(this).val() == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng chọn hình ảnh",
                icon: "warning",
                showCancelButton: false,
            });
            $(this).val("");
            $(".preview-image").html('<i class="fa fa-image"></i>');
            return;
        }

        const file = $(this)[0].files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            $(".preview-image").html(`<img src="${e.target.result}" >`);
        };
        reader.readAsDataURL(file);
    });

    $(document).on("change", "#update-product-image", function () {
        if ($(this).val() == null || $(this).val() == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng chọn hình ảnh",
                icon: "warning",
                showCancelButton: false,
            });
            $(this).val("");
            $(".preview-image-update").html('<i class="fa fa-image"></i>');
            return;
        }

        const file = $(this)[0].files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
            $(".preview-image-update").html(`<img src="${e.target.result}" >`);
        };
        reader.readAsDataURL(file);
    });

    $(document).on("click", "#btn-create-product", function () {
        const name = $("#product-name").val();
        const price = $("#product-price").val();
        const image = $("#image")[0].files[0];
        const description = $("#product-description").val();
        const category_id = $("#product-category").val();
        const slug = $("#product-slug").val();

        if (name == "" || price == "" || slug == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        if (image == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng chọn hình ảnh",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        var formData = new FormData();
        formData.append("name", name);
        formData.append("price", price);
        formData.append("image", image);
        formData.append("description", description);
        formData.append("category", category_id);
        formData.append("slug", slug);

        $.ajax({
            url: baseURL + "admin/product-manager/create",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (data) {
                if (!data.status) {
                    swal({
                        title: "Thông báo",
                        text: data.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }
                swal({
                    title: "Thông báo",
                    text: data.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    $("#product-manager")
                        .DataTable()
                        .row.add([
                            data.data.id,
                            `<img src="${baseURL}Images/${data.data.avatar}">`,
                            data.data.name,
                            data.data.category,
                            `<button id="btn-update-product" class="btn btn-blue" data-id="${data.data.id}">Cập nhật</button>
                        <button id="btn-delete-product" class="btn btn-red" data-id="${data.data.id}">Xóa</button>`,
                        ])
                        .draw();
                    closeModal();
                    clearModal("modal-create-product");
                });
            },
        });
    });

    $(document).on("click", "#btn-update-product", function () {
        const id = $(this).attr("data-id");
        openLoading();
        clearModal("modal-update-product");

        $.ajax({
            url: baseURL + "admin/product-manager",
            type: "POST",
            data: {
                id: id,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                $("#update-product-name").val(response.data.name);
                $("#update-product-price").val(response.data.price);
                $("#update-product-description").val(response.data.description);
                $("#update-product-category")
                    .val(response.data.category)
                    .trigger("change");
                $("#update-product-slug").val(response.data.slug);
                $("#update-product-id").val(id);

                $(".preview-image-update").html(
                    `<img src="${baseURL}Images/${response.data.avatar}">`
                );

                openModal("modal-update-product");
            },
        });
    });

    $(document).on("click", "#btn-update-product-modal", function () {
        const id = $("#update-product-id").val();
        const name = $("#update-product-name").val();
        const price = $("#update-product-price").val();
        const description = $("#update-product-description").val();
        const category = $("#update-product-category").val();
        const slug = $("#update-product-slug").val();
        const image = $("#update-product-image")[0].files[0] || "";

        if (name == "" || price == "" || slug == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        var formData = new FormData();
        formData.append("id", id);
        formData.append("name", name);
        formData.append("price", price);
        formData.append("description", description);
        formData.append("category", category);
        formData.append("slug", slug);
        formData.append("image", image);

        $.ajax({
            url: baseURL + "admin/product-manager/update",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    const newData = [
                        response.data.id,
                        `<img src="${baseURL}Images/${response.data.avatar}">`,
                        response.data.name,
                        response.data.category,
                        `<button id="btn-update-product" class="btn btn-blue" data-id="${response.data.id}">Cập nhật</button>
                        <button id="btn-delete-product" class="btn btn-red" data-id="${response.data.id}">Xóa</button>`,
                    ];

                    clearModal("modal-update-product");
                    $("#product-manager")
                        .DataTable()
                        .row(
                            $(
                                `#btn-update-product[data-id="${response.data.id}"]`
                            ).parents("tr")
                        )
                        .data(newData)
                        .draw();

                    closeModal();
                    clearModal("modal-update-product");
                });
            },
        });
    });

    $(document).on("click", "#btn-delete-product", function () {
        const id = $(this).attr("data-id");

        swal({
            title: "Thông báo",
            text: "Bạn có chắc chắn muốn xóa sản phẩm này?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function (result) {
            if (result) {
                openLoading();
                $.ajax({
                    url: baseURL + "admin/product-manager/delete",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                    },
                    data: {
                        id: id,
                    },
                    success: function (response) {
                        closeLoading();
                        if (!response.status) {
                            swal({
                                title: "Thông báo",
                                text: response.message,
                                icon: "warning",
                                showCancelButton: false,
                            });
                            return;
                        }
                        swal({
                            title: "Thông báo",
                            text: response.message,
                            icon: "success",
                            showCancelButton: false,
                        }).then(function () {
                            $("#product-manager")
                                .DataTable()
                                .row(
                                    $(
                                        `#btn-delete-product[data-id="${id}"]`
                                    ).parents("tr")
                                )
                                .remove()
                                .draw();
                        });
                    },
                });
            }
        });
    });

    $(document).on("click", "#btn-add-product-to-cart", function () {
        const id = $(this).attr("data-id");
        
        // Nếu đang ở trang chi tiết sản phẩm thì sẽ không thực hiện hàm này
        // vì trang chi tiết sẽ sử dụng hàm toàn cục addProductToCart
        if ($(this).hasClass('btn-add-to-cart')) {
            return;
        }

        addProductToCart(id, 1);
    });

    $(document).on("click", "#down-count-product", function () {
        const id = $(this).attr("data-id");
        const count = parseInt($(`#count-product[data-id="${id}"]`).html());

        if (count <= 1) {
            return;
        }

        $.ajax({
            url: baseURL + "dashboard/orders/update-count",
            type: "POST",
            data: {
                product_id: id,
                count: count - 1,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                $(`#count-product[data-id="${id}"]`).html(count - 1);
                $(`#total_price[data-id="${id}"]`).html(
                    convertPrice(response.data.total_price)
                );

                $("#total_all_product").html(
                    convertPrice(response.data.total_all)
                );
                $("#into_money").html(
                    convertPrice(response.data.total_discount)
                );
            },
        });
    });

    $(document).on("click", "#up-count-product", function () {
        const id = $(this).attr("data-id");
        const count = parseInt($(`#count-product[data-id="${id}"]`).html());

        $.ajax({
            url: baseURL + "dashboard/orders/update-count",
            type: "POST",
            data: {
                product_id: id,
                count: count + 1,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                $(`#count-product[data-id="${id}"]`).html(count + 1);
                $(`#total_price[data-id="${id}"]`).html(
                    convertPrice(response.data.total_price)
                );
                $("#total_all_product").html(
                    convertPrice(response.data.total_all)
                );
                $("#into_money").html(
                    convertPrice(response.data.total_discount)
                );
            },
        });
    });

    $(document).on("click", "#btn-delete-product-in-cart", function () {
        const id = $(this).attr("data-id");

        swal({
            title: "Thông báo",
            text: "Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function (result) {
            if (result) {
                $.ajax({
                    url: baseURL + "dashboard/orders/delete",
                    type: "POST",
                    data: {
                        product_id: id,
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                    },
                    success: function (response) {
                        if (!response.status) {
                            swal({
                                title: "Thông báo",
                                text: response.message,
                                icon: "warning",
                                showCancelButton: false,
                            });
                            return;
                        }

                        swal({
                            title: "Thông báo",
                            text: response.message,
                            icon: "success",
                            showCancelButton: false,
                        }).then(function () {
                            $("#orders-guest")
                                .DataTable()
                                .row(
                                    $(
                                        `#btn-delete-product-in-cart[data-id="${id}"]`
                                    ).parents("tr")
                                )
                                .remove()
                                .draw();

                            $("#total_all_product").html(
                                convertPrice(response.data.total_all)
                            );
                            $("#into_money").html(
                                convertPrice(response.data.total_discount)
                            );

                            if (response.data.is_empty) {
                                $(".apply").addClass("hide");
                            }
                        });
                    },
                });
            }
        });
    });

    function convertPrice(price) {
        // Chuyển đổi giá tiền thành định dạng 1,000,000
        return price.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + "đ";
    }

    $(document).on("click", "#btn-apply-discount", function () {
        const code = $("#discount-code").val();

        if (code == "") {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập mã giảm giá",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openLoading();
        $.ajax({
            url: baseURL + "dashboard/orders/apply-discount",
            type: "POST",
            data: {
                code: code,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    $("#into_money").html(
                        convertPrice(response.data.total_price)
                    );
                    $(".apply>strong").html(response.data.name);
                    $(".apply").removeClass("hide");
                });
            },
        });
    });

    // Show payment options modal
    $(document).on("click", "#btn-show-payment-options", function () {
        // Nếu bảng orders-guest không có dữ liệu thì không cho đặt hàng
        if ($("#orders-guest").DataTable().data().count() == 0) {
            swal({
                title: "Thông báo",
                text: "Chưa có sản phẩm trong giỏ hàng",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }

        openModal("modal-order-pay");
    });

    // Handle radio button changes in payment modal
    $(document).on("change", "input[name='pay_method']", function() {
        // Hide all payment info
        $(".pay-info").addClass("hide");
        
        // Show the selected payment info
        if ($(this).val() === "vnpay") {
            $("#vnpay-info").removeClass("hide");
        }
    });

    // Handle payment button click
    $(document).on("click", "#btn-pay", function () {
        const paymentMethod = $("input[name='pay_method']:checked").val();
        const shippingAddress = $("#shipping_address").val();
        const shippingPhone = $("#shipping_phone").val();
        
        // Validate shipping info
        if (!shippingAddress || !shippingPhone) {
            swal({
                title: "Thông báo",
                text: "Vui lòng nhập đầy đủ thông tin giao hàng",
                icon: "warning",
                showCancelButton: false,
            });
            return;
        }
        
        closeModal("modal-order-pay");
        
        if (paymentMethod === "vnpay") {
            // Get total amount from page
            const totalAmount = $("#into_money").text().replace(/\D/g, "");
            
            openLoading();
            // Redirect to VNPAY payment gateway
            $.ajax({
                url: baseURL + "dashboard/orders/vnpay-payment",
                type: "POST",
                data: {
                    amount: totalAmount,
                    shipping_address: shippingAddress,
                    shipping_phone: shippingPhone
                },
                headers: {
                    "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                },
                success: function (response) {
                    closeLoading();
                    
                    if (!response.status) {
                        swal({
                            title: "Thông báo",
                            text: response.message,
                            icon: "warning",
                            showCancelButton: false,
                        });
                        return;
                    }
                    
                    // Redirect to VNPAY
                    window.location.href = response.data.redirect_url;
                },
            });
        } else {
            // Process direct payment
            openLoading();
            
            $.ajax({
                url: baseURL + "dashboard/orders/order-closing",
                type: "POST",
                data: {
                    payment_method: paymentMethod,
                    shipping_address: shippingAddress,
                    shipping_phone: shippingPhone
                },
                headers: {
                    "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                },
                success: function (response) {
                    closeLoading();
                    if (!response.status) {
                        swal({
                            title: "Thông báo",
                            text: response.message,
                            icon: "warning",
                            showCancelButton: false,
                        });
                        return;
                    }
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "success",
                        showCancelButton: false,
                    }).then(function () {
                        window.location.reload();
                    });
                },
            });
        }
    });

    $(document).on("click", "#btn-order-detail", function () {
        const id = $(this).attr("data-id");

        openLoading();

        $.ajax({
            url: baseURL + "admin/order-manager/detail",
            type: "POST",
            data: {
                order_id: id,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                $("#order-detail").DataTable().clear().draw();

                response.data.products.forEach((element) => {
                    const data = [
                        element.id,
                        `<img src="${baseURL}Images/${element.avatar}">`,
                        element.name,
                        element.quantity,
                    ];

                    $("#order-detail").DataTable().row.add(data).draw();
                });

                // Display shipping information
                $("#order-shipping-address").text(response.data.shipping_address || "Không có");
                $("#order-shipping-phone").text(response.data.shipping_phone || "Không có");

                openModal("modal-order-detail");
            },
        });
    });

    $(document).on("click", "#btn-order-browsing", function () {
        const id = $(this).attr("data-id");
        openLoading();

        $.ajax({
            url: baseURL + "admin/order-manager/browsing",
            type: "POST",
            data: {
                order_id: id,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                // Update the status in the table instead of removing the row
                const $row = $(`#btn-order-browsing[data-id="${id}"]`).closest('tr');
                
                // Change the button to show it's approved
                $row.find('#btn-order-browsing')
                    .removeClass('btn-green')
                    .addClass('btn-gray')
                    .text('Đã duyệt')
                    .prop('disabled', true);
                
                // Update the status in the table
                $row.find('td:nth-child(4)').text('Đã duyệt');
                
                swal({
                    title: "Thành công",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                });
            },
        });
    });

    $(document).on("click", "#btn-order-detail-shipper", function () {
        const id = $(this).attr("data-id");

        openLoading();

        $.ajax({
            url: baseURL + "shipper/order-approved/detail",
            type: "POST",
            data: {
                order_id: id,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                $("#order-detail").DataTable().clear().draw();

                response.data.products.forEach((element) => {
                    const data = [
                        element.id,
                        `<img src="${baseURL}Images/${element.avatar}">`,
                        element.name,
                        element.quantity,
                    ];

                    $("#order-detail").DataTable().row.add(data).draw();
                });

                // Display shipping information
                $("#order-shipping-address").text(response.data.shipping_address || "Không có");
                $("#order-shipping-phone").text(response.data.shipping_phone || "Không có");

                openModal("modal-order-detail");
            },
        });
    });

    $(document).on("click", "#btn-order-detail-guest", function () {
        const id = $(this).attr("data-id");

        openLoading();

        $.ajax({
            url: baseURL + "dashboard/orders/detail",
            type: "POST",
            data: {
                order_id: id,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                $("#order-detail").DataTable().clear().draw();

                response.data.products.forEach((element) => {
                    const data = [
                        element.id,
                        `<img src="${baseURL}Images/${element.avatar}">`,
                        element.name,
                        element.quantity,
                    ];

                    $("#order-detail").DataTable().row.add(data).draw();
                });

                // Display shipping information
                $("#order-shipping-address").text(response.data.shipping_address || "Không có");
                $("#order-shipping-phone").text(response.data.shipping_phone || "Không có");

                openModal("modal-order-detail");
            },
        });
    });

    $(document).on("click", "#btn-order-review-guest", function () {
        const id = $(this).attr("data-id");

        openLoading();

        $.ajax({
            url: baseURL + "dashboard/orders/review/get",
            type: "POST",
            data: {
                order_id: id,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();
                clearModal("modal-order-review");

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });

                    return;
                }

                $("#review-note").val(response.data.note);
                $("#order-review").DataTable().clear().draw();

                response.data.products.forEach((element) => {
                    const data = [
                        element.product_id,
                        `<img src="${baseURL}Images/${element.avatar}">`,
                        element.name,
                        `
                            <select class="select-box" id="review-star" data-id="${element.product_id}">
                                <option value="" ${element.vote == null ? "selected" : ""}></option>
                                <option value="1" ${element.vote == 1 ? "selected" : ""}>1</option>
                                <option value="2" ${element.vote == 2 ? "selected" : ""}>2</option>
                                <option value="3" ${element.vote == 3 ? "selected" : ""}>3</option>
                                <option value="4" ${element.vote == 4 ? "selected" : ""}>4</option>
                                <option value="5" ${element.vote == 5 ? "selected" : ""}>5</option>
                            </select>
                        `
                    ];

                    $("#btn-review").attr("data-order-id", id);

                    $("#order-review").DataTable().row.add(data).draw();
                });

                openModal("modal-order-review");
            }
        });
    });

    $(document).on('click', '#btn-review', function(event) {
        event.preventDefault();
        
        var data = [];
        const order_id = $(this).attr("data-order-id");
        const note = $("#review-note").val();

        const reviews = $("#order-review").DataTable().rows().data();
        reviews.each(function (value, index) {
            if (!isNaN(parseInt($(`#review-star[data-id="${value[0]}"]`).val())))
                data.push({
                    id: parseInt(value[0]),
                    vote: parseInt($(`#review-star[data-id="${value[0]}"]`).val()),
                    name: value[2]
                });
        });

        if (data.length == 0) {
            swal({
                title: "Thông báo",
                text: "Vui lòng chọn đánh giá ít nhất 1 sản phẩm",
                icon: "warning",
                showCancelButton: false,
            });

            return;
        }

        openLoading();

        $.ajax({
            url: baseURL + "dashboard/orders/review",
            type: "POST",
            data: {
                data: data,
                order_id: order_id,
                note: note
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });

                    return;
                }

                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function() {
                    clearModal("modal-order-review");
                    closeModal("modal-order-review");
                });
            }
        });
    });

    $(document).on("click", "#btn-order-receiving", function () {
        const id = $(this).attr("data-id");
        openLoading();

        $.ajax({
            url: baseURL + "shipper/order-approved/receiving",
            type: "POST",
            data: {
                order_id: id,
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    $("#order-approved")
                        .DataTable()
                        .row(
                            $(`#btn-order-receiving[data-id="${id}"]`).parents(
                                "tr"
                            )
                        )
                        .remove()
                        .draw();

                    window.location.reload();
                });
            },
        });
    });

    $(document).on("click", "#btn-order-complete", function () {
        openLoading();

        $.ajax({
            url: baseURL + "shipper/order-approved/complete",
            type: "POST",
            data: {},
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();

                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                    timer: 4000
                }).then(function () {
                    window.location.reload();
                });
            },
        });
    });

    $(document).on("click", "#btn-order-cancel-guest", function () {
        const id = $(this).attr("data-id");

        swal({
            title: "Thông báo",
            text: "Bạn có chắc chắn muốn hủy đơn hàng này?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function (result) {
            if (result) {
                openLoading();

                $.ajax({
                    url: baseURL + "dashboard/orders/cancel",
                    type: "POST",
                    data: {
                        order_id: id,
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                    },
                    success: function (response) {
                        closeLoading();

                        if (!response.status) {
                            swal({
                                title: "Thông báo",
                                text: response.message,
                                icon: "warning",
                                showCancelButton: false,
                            });
                            return;
                        }

                        swal({
                            title: "Thông báo",
                            text: response.message,
                            icon: "success",
                            showCancelButton: false,
                        }).then(function () {
                            window.location.reload();
                        });
                    },
                });
            }
        });
    });

    $(document).on("click", "#btn-order-cancel-admin", function () {
        const id = $(this).attr("data-id");

        swal({
            title: "Thông báo",
            text: "Bạn có chắc chắn muốn hủy đơn hàng này?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function (result) {
            if (result) {
                openLoading();

                $.ajax({
                    url: baseURL + "admin/order-manager/cancel",
                    type: "POST",
                    data: {
                        order_id: id,
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                    },
                    success: function (response) {
                        closeLoading();

                        if (!response.status) {
                            swal({
                                title: "Thông báo",
                                text: response.message,
                                icon: "warning",
                                showCancelButton: false,
                            });
                            return;
                        }

                        swal({
                            title: "Thông báo",
                            text: response.message,
                            icon: "success",
                            showCancelButton: false,
                        }).then(function () {
                            window.location.reload();
                        });
                    },
                });
            }
        });
    });

    // Hàm toàn cục để thêm sản phẩm vào giỏ hàng với số lượng tùy chọn
    function addProductToCart(productId, quantity = 1) {
        openLoading();
        $.ajax({
            url: baseURL + "dashboard/orders",
            type: "POST",
            data: {
                product_id: productId,
                quantity: quantity
            },
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').val(),
            },
            success: function (response) {
                closeLoading();
                if (!response.status) {
                    swal({
                        title: "Thông báo",
                        text: response.message,
                        icon: "warning",
                        showCancelButton: false,
                    });
                    return;
                }

                swal({
                    title: "Thông báo",
                    text: response.message,
                    icon: "success",
                    showCancelButton: false,
                }).then(function () {
                    if ($("#cart-count").length > 0) {
                        $("#cart-count").html(response.data.count);
                    }
                });
            },
        });
    }
});
