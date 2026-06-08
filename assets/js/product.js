document.addEventListener("DOMContentLoaded", function () {
    console.log("PRODUCT JS LOADED");

    const mainImage = document.getElementById("mzMainProductImage");
    const thumbs = Array.from(document.querySelectorAll(".mz-thumb"));
    const qtyInput = document.getElementById("mzPdpQty");

    let currentIndex = 0;

    function setImage(index) {
        if (!mainImage || !thumbs[index]) return;

        currentIndex = index;
        mainImage.src = thumbs[index].dataset.img;

        thumbs.forEach((thumb) => {
            thumb.classList.remove("border-[#9fbd58]", "border-2");
            thumb.classList.add("border-[#ead6c8]");
        });

        thumbs[index].classList.remove("border-[#ead6c8]");
        thumbs[index].classList.add("border-[#9fbd58]", "border-2");
    }

    thumbs.forEach((thumb, index) => {
        thumb.addEventListener("click", function () {
            setImage(index);
        });
    });

    document.querySelector(".mz-pdp-qty-plus")?.addEventListener("click", function () {
        if (!qtyInput) return;
        qtyInput.value = parseInt(qtyInput.value || "1", 10) + 1;
    });

    document.querySelector(".mz-pdp-qty-minus")?.addEventListener("click", function () {
        if (!qtyInput) return;

        const currentQty = parseInt(qtyInput.value || "1", 10);

        if (currentQty > 1) {
            qtyInput.value = currentQty - 1;
        }
    });

    const popup = document.getElementById("mzImagePopup");
    const popupImage = document.getElementById("mzPopupImage");
    const closePopupBtn = document.getElementById("mzClosePopup");
    const zoomBtn = document.getElementById("mzZoomBtn");
    const popupPrev = document.getElementById("mzPopupPrev");
    const popupNext = document.getElementById("mzPopupNext");

    function openPopup() {
        if (!popup || !popupImage || !mainImage) return;

        popup.classList.remove("hidden");
        popup.classList.add("flex");
        popupImage.src = mainImage.src;
        document.body.style.overflow = "hidden";
    }

    function closePopup() {
        if (!popup) return;

        popup.classList.add("hidden");
        popup.classList.remove("flex");
        document.body.style.overflow = "";
    }

    function popupGo(direction) {
        if (!thumbs.length) return;

        let nextIndex = currentIndex + direction;

        if (nextIndex < 0) {
            nextIndex = thumbs.length - 1;
        }

        if (nextIndex >= thumbs.length) {
            nextIndex = 0;
        }

        setImage(nextIndex);

        if (popupImage && mainImage) {
            popupImage.src = mainImage.src;
        }
    }

    mainImage?.addEventListener("click", openPopup);
    zoomBtn?.addEventListener("click", openPopup);
    closePopupBtn?.addEventListener("click", closePopup);

    popupPrev?.addEventListener("click", function () {
        popupGo(-1);
    });

    popupNext?.addEventListener("click", function () {
        popupGo(1);
    });

    popup?.addEventListener("click", function (event) {
        if (event.target === popup) {
            closePopup();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (!popup || popup.classList.contains("hidden")) return;

        if (event.key === "Escape") {
            closePopup();
        }

        if (event.key === "ArrowLeft") {
            popupGo(-1);
        }

        if (event.key === "ArrowRight") {
            popupGo(1);
        }
    });

    document.querySelectorAll(".mz-accordion-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            const content = this.nextElementSibling;
            const icon = this.querySelector(".mz-acc-icon");

            if (!content) return;

            content.classList.toggle("hidden");

            if (icon) {
                icon.textContent = content.classList.contains("hidden") ? "+" : "−";
            }
        });
    });

    document.querySelector(".mzAjaxAddCart")?.addEventListener("click", function () {
        const btn = this;
        const productId = btn.dataset.productId;
        const qty = qtyInput ? qtyInput.value : 1;

        btn.disabled = true;
        btn.textContent = "Adding...";

        fetch(meziva_ajax.ajax_url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                action: "meziva_ajax_add_cart",
                product_id: productId,
                quantity: qty,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                btn.disabled = false;
                btn.textContent = "Add To Cart";

                if (!data.success) return;

                const cartCount = document.querySelector(".mz-cart-count");
                if (cartCount) {
                    cartCount.textContent = data.data.count;
                }

                if (typeof window.refreshCart === "function") {
                    window.refreshCart(true);
                } else {
                    const cartBtn = document.querySelector(".mz-open-cart");
                    if (cartBtn) {
                        cartBtn.click();
                    }
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = "Add To Cart";
            });
    });
});