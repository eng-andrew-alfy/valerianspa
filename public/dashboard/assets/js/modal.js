"use strict";
$(document).ready(function () {
    // Basic alert
    var sweet1Button = document.querySelector(".sweet-1");
    if (sweet1Button) {
        sweet1Button.onclick = function () {
            swal("Here's a message!", "It's pretty, isn't it?");
        };
    }

    // Success message
    var alertSuccessButton = document.querySelector(".alert-success-msg");
    if (alertSuccessButton) {
        alertSuccessButton.onclick = function () {
            swal("Good job!", "You clicked the button!", "success");
        };
    }

    // Alert confirm
    var alertConfirmButtons = document.querySelectorAll(".alert-confirm");
    if (alertConfirmButtons.length > 0) {
        alertConfirmButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const route = this.getAttribute("data-route");
                const csrfToken = this.getAttribute("data-csrf-token");

                Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this imaginary file!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel",
                    reverseButtons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(route, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Content-Type": "application/json",
                            },
                        })
                            .then((response) => {
                                if (!response.ok) {
                                    throw new Error(
                                        "Network response was not ok."
                                    );
                                }
                                return response.json();
                            })
                            .then((data) => {
                                if (data.success) {
                                    Swal.fire(
                                        "Deleted!",
                                        "Your imaginary file has been deleted.",
                                        "success"
                                    );
                                    location.reload();
                                } else {
                                    Swal.fire(
                                        "Failed!",
                                        "Failed to delete the file.",
                                        "error"
                                    );
                                }
                            })
                            .catch((error) => {
                                console.error("Error:", error);
                                Swal.fire(
                                    "Error!",
                                    "An error occurred while deleting the file.",
                                    "error"
                                );
                            });
                    }
                });
            });
        });
    }

    // Success or cancel alert
    var alertSuccessCancel = document.querySelector(".alert-success-cancel");
    if (alertSuccessCancel) {
        alertSuccessCancel.onclick = function () {
            swal(
                {
                    title: "Are you sure?",
                    text: "You will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel plx!",
                    closeOnConfirm: false,
                    closeOnCancel: false,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        swal(
                            "Deleted!",
                            "Your imaginary file has been deleted.",
                            "success"
                        );
                    } else {
                        swal(
                            "Cancelled",
                            "Your imaginary file is safe :)",
                            "error"
                        );
                    }
                }
            );
        };
    }

    // Prompt alert
    var alertPrompt = document.querySelector(".alert-prompt");
    if (alertPrompt) {
        alertPrompt.onclick = function () {
            swal(
                {
                    title: "An input!",
                    text: "Write something interesting:",
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    inputPlaceholder: "Write something",
                },
                function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false;
                    }
                    swal("Nice!", "You wrote: " + inputValue, "success");
                }
            );
        };
    }

    // Ajax alert
    var alertAjax = document.querySelector(".alert-ajax");
    if (alertAjax) {
        alertAjax.onclick = function () {
            swal(
                {
                    title: "Ajax request example",
                    text: "Submit to run ajax request",
                    type: "info",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                },
                function () {
                    setTimeout(function () {
                        swal("Ajax request finished!");
                    }, 2000);
                }
            );
        };
    }

    // Modal handling
    var openBtn = document.querySelector("#openBtn");
    if (openBtn) {
        openBtn.addEventListener("click", function () {
            $("#myModal").modal({
                show: true,
            });
        });
    }

    $(document).on("show.bs.modal", ".modal", function (event) {
        var zIndex = 1040 + 10 * $(".modal:visible").length;
        $(this).css("z-index", zIndex);
        setTimeout(function () {
            $(".modal-backdrop")
                .not(".modal-stack")
                .css("z-index", zIndex - 1)
                .addClass("modal-stack");
        }, 0);
    });
});
