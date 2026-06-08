document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("mzCartOverlay");
    const cart = document.getElementById("mzMiniCart");
    const content = document.getElementById("mzMiniCartContent");

    function openCart() {
        overlay?.classList.add("is-open");
        cart?.classList.add("is-open");
        document.body.classList.add("mz-cart-open");
    }

    function closeCart() {
        overlay?.classList.remove("is-open");
        cart?.classList.remove("is-open");
        document.body.classList.remove("mz-cart-open");
    }

    function updateCount(count) {
        document.querySelectorAll(".mz-cart-count").forEach((el) => {
            el.textContent = count;
        });
    }

      window.refreshCart = function refreshCart(open = true) {
        const formData = new FormData();
        formData.append("action", "meziva_get_minicart");
        formData.append("nonce", meziva_cart_ajax.nonce);

        fetch(meziva_cart_ajax.ajax_url, {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                if (!data.success) return;

                content.innerHTML = data.data.html;
                updateCount(data.data.count);

                if (open) openCart();
            });
    }

    document.addEventListener("click", function (e) {
        const cartBtn = e.target.closest(".mz-open-cart");

        if (cartBtn) {
            e.preventDefault();
            refreshCart(true);
            return;
        }

        if (e.target.closest(".mz-cart-close") || e.target === overlay) {
            closeCart();
            return;
        }

        const addBtn = e.target.closest(".meziva-ajax-add-cart");

        if (addBtn) {
            e.preventDefault();

            const productId = addBtn.dataset.productId;
            const oldText = addBtn.textContent;

            addBtn.textContent = "Adding...";
            addBtn.disabled = true;

            const formData = new FormData();
            formData.append("action", "meziva_add_to_cart_product");
            formData.append("nonce", meziva_cart_ajax.nonce);
            formData.append("product_id", productId);
            formData.append("quantity", "1");

            fetch(meziva_cart_ajax.ajax_url, {
                method: "POST",
                body: formData,
            })
                .then((res) => res.json())
                .then((data) => {
                    addBtn.textContent = oldText;
                    addBtn.disabled = false;

                    if (!data.success) return;

                    content.innerHTML = data.data.html;
                    updateCount(data.data.count);
                    openCart();
                });

            return;
        }

        const plus = e.target.closest(".mz-qty-plus");
        const minus = e.target.closest(".mz-qty-minus");
        const remove = e.target.closest(".mz-cart-remove");

        if (plus || minus || remove) {
            const item = e.target.closest(".mz-cart-item");
            if (!item) return;

            const key = item.dataset.key;
            const qtyEl = item.querySelector(".mz-cart-qty span");
            let qty = parseInt(qtyEl.textContent || "1", 10);

            if (plus) qty += 1;
            if (minus) qty -= 1;
            if (remove) qty = 0;

            const formData = new FormData();
            formData.append("action", "meziva_update_minicart");
            formData.append("nonce", meziva_cart_ajax.nonce);
            formData.append("cart_item_key", key);
            formData.append("quantity", qty);

            fetch(meziva_cart_ajax.ajax_url, {
                method: "POST",
                body: formData,
            })
                .then((res) => res.json())
                .then((data) => {
                    if (!data.success) return;

                    content.innerHTML = data.data.html;
                    updateCount(data.data.count);
                });
        }
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeCart();
    });
});