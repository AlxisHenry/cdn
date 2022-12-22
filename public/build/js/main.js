const icons = document.querySelectorAll(".edit-item, .delete-item");

icons.forEach((icon) => {
    icon.addEventListener("click", () => {
        let item = icon.parentNode.parentNode.parentNode;
        let container = icon.parentNode;
        let properties = {
            action: icon.dataset.action,
            category: item.dataset.category,
            filename: item.dataset.filename,
            filepath: item.dataset.filepath,
            token: container.dataset.token,
        };
        switch (properties.action) {
            case "edit":
                Swal.fire({
                    title: "Enter new filename",
                    input: "text",
                    icon: "info",
                    iconColor: "#464aa6",
                    inputLabel: "New filename",
                    inputValue: properties.filename,
                    showCancelButton: true,
                    confirmButtonText: "Yes, rename it!",
                    confirmButtonColor: "#464aa6",
                    cancelButtonColor: "#242b40",
                }).then((response) => {
                    if (response.isConfirmed) {
                        Swal.fire({
                            title: "Oupss!",
                            text: "This feature is not yet implemented",
                            icon: "error",
                            iconColor: "#464aa6",
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 3000,
                        });
                    }
                });
                break;
            case "delete":
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    iconColor: "#464aa6",
                    showCancelButton: true,
                    confirmButtonColor: "#464aa6",
                    cancelButtonColor: "#242b40",
                    confirmButtonText: "Yes, delete it!",
                }).then((response) => {
                    if (response.isConfirmed) {
                        fetch("/files.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                token: properties.token,
                            },
                            body: JSON.stringify(properties),
                        }).then((response) => {
                            if (response.ok) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    icon: "success",
                                    iconColor: "#464aa6",
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                    allowOutsideClick: true,
                                    allowEscapeKey: true,
                                    timer: 3000,
                                });
                                item.classList.add("remove-item-animation");
                                setTimeout(() => {
                                    item.remove();
                                }, 700);
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Something went wrong.",
                                    icon: "error",
                                    iconColor: "#464aa6",
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                    timer: 3000,
                                });
                            }
                            response.text().then((text) => {
                                console.log(text);
                            })
                        }).catch(() => {
                            Swal.fire({
                                title: "Error!",
                                text: "Something went wrong.",
                                icon: "error",
                                iconColor: "#464aa6",
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 3000,
                            });
                        })
                    }
                });
                break;
        }
    });
});