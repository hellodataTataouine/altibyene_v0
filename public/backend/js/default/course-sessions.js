"use strict";

/** Variables */
const supported_source = [
    "upload",
    "youtube",
    "vimeo",
    "external_link",
    "google_drive",
    "iframe",
    "aws",
];
const dynamicModalContent = $(".dynamic-modal .modal-content .modal-body");

/** Template Variables */
const loader = `
<div class="d-flex justify-content-center align-items:center p-3">
  <div class="spinner-border" role="status">
    <span class="visually-hidden"></span>
  </div>
</div>`;

/** Functions */

/** On Document Load */
$(document).ready(function () {

    /** Delete item */
    $(".delete-item").on("click", function (e) {
        e.preventDefault();
        let url = $(this).attr("href");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "POST",
                    url: url,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    beforeSend: function () {},
                    success: function (data) {
                        if (data.status == "success") {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your file has been deleted.",
                                icon: "success",
                            });
                            location.reload();
                        }
                    },
                    error: function (xhr, status, error) {
                        toastr.error(error);
                    },
                });
            }
        });
    });
});
