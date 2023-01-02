const icons = document.querySelectorAll(".edit-item, .delete-item");

const alert = (properties) => {
    const { type, title, text, color } = {
        type: properties.type,
        title: properties.title ?? "Oops...",
        text: properties.text ?? "Something went wrong!",
        color: properties.type === "success" ? "#464aa6" : "#242b40",
    };
    Swal.fire({
        title: title,
        text: text,
        icon: type,
        iconColor: color,
        showConfirmButton: false,
        timerProgressBar: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        timer: 3000,
    });
};

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
                        fetch("/files.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                ...properties,
                                newFilename: response.value ?? "",
                            }),
                        })
                            .then((response) => {
                                if (response.ok) {
                                    location.reload();
                                } else {
                                    alert({ type: "error" });
                                }
                            })
                            .catch(() => {
                                alert({ type: "error" });
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
                            },
                            body: JSON.stringify(properties),
                        })
                            .then((response) => {
                                if (response.ok) {
                                    alert({
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        type: "success",
                                    });
                                    document
                                        .querySelectorAll(
                                            `[data-filename="${properties.filename}"]`
                                        )
                                        .forEach((item) => {
                                            item.classList.add(
                                                "remove-item-animation"
                                            );
                                            setTimeout(() => {
                                                item.remove();
                                            }, 700);
                                        });
                                } else {
                                    alert({ type: "error" });
                                }
                            })
                            .catch(() => {
                                alert({ type: "error" });
                            });
                    }
                });
                break;
        }
    });
});

const zip = document.querySelector("#zip");

if (zip) {
    const zipItems = zip.querySelectorAll("li");
    zipItems.forEach((item) => {
        item.addEventListener("click", () => {
            if (item.classList.contains("active-item")) {
                item.classList.remove("active-item");
                item.classList.add("cancel-active-item");
                setTimeout(() => {
                    item.classList.remove("cancel-active-item");
                }, 500);
            } else {
                item.classList.add("active-item");
            }
        });
    });
}

const download = document.querySelector("#download-button");

if (download) {
    download.addEventListener("click", () => {
        const activeItems = document.querySelectorAll(".active-item");
        if (!activeItems || activeItems.length === 0) {
            alert({ type: "error", text: "No files selected." });
            return;
        }
        let properties = {
            action: "zip",
            items: [].map.call(activeItems, (item) => {
                return item.dataset.filepath;
            }),
        };
        const uri =
            "/files.php?action=zip&items=" + JSON.stringify(properties.items);
        const encoded = encodeURI(uri);
        location.href = encoded;
        alert({
            title: "Downloaded!",
            text: "Your files have been downloaded.",
            type: "success",
        });
        document.querySelectorAll(".active-item").forEach((item) => {
            item.classList.remove("active-item");
        });
    });
}
