document.addEventListener("DOMContentLoaded", function () {
  const images = window.MZ_PDP_IMAGES || [];
  let currentIndex = 0;

  const mainImg = document.getElementById("mzMainProductImage");
  const thumbs = document.querySelectorAll(".mz-thumb");

  const popup = document.getElementById("mzImagePopup");
  const popupImg = document.getElementById("mzPopupImage");

  function setImage(index) {
    if (!images[index] || !mainImg) return;

    currentIndex = index;
    mainImg.src = images[index];

    thumbs.forEach((thumb) => {
      const isActive = Number(thumb.dataset.index) === index;
      thumb.classList.toggle("border-primary", isActive);
      thumb.classList.toggle("border-transparent", !isActive);
    });
  }

  thumbs.forEach((thumb) => {
    thumb.addEventListener("click", function () {
      setImage(Number(this.dataset.index || 0));
    });
  });

  function openPopup() {
    if (!popup || !popupImg) return;
    popupImg.src = images[currentIndex] || mainImg?.src || "";
    popup.classList.remove("hidden");
    popup.classList.add("flex");
    document.body.style.overflow = "hidden";
  }

  function closePopup() {
    popup?.classList.add("hidden");
    popup?.classList.remove("flex");
    document.body.style.overflow = "";
  }

  function popupNext() {
    currentIndex = (currentIndex + 1) % images.length;
    popupImg.src = images[currentIndex];
    setImage(currentIndex);
  }

  function popupPrev() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    popupImg.src = images[currentIndex];
    setImage(currentIndex);
  }

  mainImg?.addEventListener("click", openPopup);
  document.getElementById("mzZoomBtn")?.addEventListener("click", openPopup);
  document.getElementById("mzClosePopup")?.addEventListener("click", closePopup);
  document.getElementById("mzPopupNext")?.addEventListener("click", popupNext);
  document.getElementById("mzPopupPrev")?.addEventListener("click", popupPrev);

  popup?.addEventListener("click", function (e) {
    if (e.target === popup) closePopup();
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closePopup();
  });

  function getQty() {
    const desktop = document.getElementById("mzPdpQty");
    const mobile = document.getElementById("mzPdpQtyMobile");

    if (window.innerWidth < 768 && mobile) {
      return Math.max(1, parseInt(mobile.value || "1", 10));
    }

    return Math.max(1, parseInt(desktop?.value || "1", 10));
  }

  function syncQty(value) {
    document.querySelectorAll("#mzPdpQty, #mzPdpQtyMobile").forEach((input) => {
      input.value = value;
    });
  }

  document.addEventListener("click", function (e) {
    const plus = e.target.closest(".mz-pdp-qty-plus");
    const minus = e.target.closest(".mz-pdp-qty-minus");

    if (plus || minus) {
      let qty = getQty();

      if (plus) qty++;
      if (minus) qty = Math.max(1, qty - 1);

      syncQty(qty);
    }
  });

  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".mzAjaxAddCart");
    if (!btn) return;

    e.preventDefault();

    const productId = btn.dataset.productId;
    const qty = getQty();
    const oldText = btn.textContent;

    btn.textContent = "Adding...";
    btn.disabled = true;

    const formData = new FormData();
    formData.append("action", "meziva_add_to_cart_product");
    formData.append("nonce", meziva_cart_ajax.nonce);
    formData.append("product_id", productId);
    formData.append("quantity", qty);

    fetch(meziva_cart_ajax.ajax_url, {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        btn.disabled = false;

        if (!data.success) {
          btn.textContent = "Try Again";
          setTimeout(() => (btn.textContent = oldText), 1200);
          return;
        }

        btn.textContent = "Added ✓";

        if (typeof window.refreshCart === "function") {
          window.refreshCart(true);
        }

        setTimeout(() => {
          btn.textContent = oldText;
        }, 1200);
      })
      .catch(() => {
        btn.disabled = false;
        btn.textContent = oldText;
      });
  });

  document.addEventListener("click", function (e) {
    const coupon = e.target.closest(".mz-copy-coupon");
    if (!coupon) return;

    const code = coupon.dataset.coupon || "";
    const msg = document.getElementById("mzCouponMsg");

    navigator.clipboard.writeText(code).then(() => {
      msg?.classList.remove("hidden");

      setTimeout(() => {
        msg?.classList.add("hidden");
      }, 1500);
    });
  });

  document.querySelectorAll(".mz-section-toggle").forEach((btn) => {
    btn.addEventListener("click", function () {
      const box = this.closest(".mz-pdp-section");
      const content = box?.querySelector(".mz-section-content");
      const icon = this.querySelector("span:last-child");

      content?.classList.toggle("hidden");

      if (icon) {
        icon.textContent = content?.classList.contains("hidden") ? "+" : "−";
      }
    });
  });

  document.querySelectorAll(".mz-faq-toggle").forEach((btn) => {
    btn.addEventListener("click", function () {
      const faq = this.closest(".mz-faq");
      const content = faq?.querySelector(".mz-faq-content");
      const icon = this.querySelector("span");

      content?.classList.toggle("hidden");

      if (icon) {
        icon.textContent = content?.classList.contains("hidden") ? "+" : "−";
      }
    });
  });
});