// Header + Search + Hero + Bestseller JS
document.addEventListener("DOMContentLoaded", function () {
  /* Topbar slider */
  const topbarSlides = Array.from(document.querySelectorAll(".meziva-topbar-slide"));
  let topbarIndex = 0;

  function showTopbarSlide(index) {
    if (!topbarSlides.length) return;

    topbarSlides.forEach((slide, i) => {
      slide.classList.toggle("opacity-100", i === index);
      slide.classList.toggle("translate-y-0", i === index);
      slide.classList.toggle("opacity-0", i !== index);
      slide.classList.toggle("translate-y-4", i !== index);
      slide.classList.toggle("is-active", i === index);
    });
  }

  function nextTopbar() {
    topbarIndex = (topbarIndex + 1) % topbarSlides.length;
    showTopbarSlide(topbarIndex);
  }

  function prevTopbar() {
    topbarIndex = (topbarIndex - 1 + topbarSlides.length) % topbarSlides.length;
    showTopbarSlide(topbarIndex);
  }

  document.getElementById("mezivaTopbarNext")?.addEventListener("click", nextTopbar);
  document.getElementById("mezivaTopbarPrev")?.addEventListener("click", prevTopbar);

  showTopbarSlide(0);

  if (topbarSlides.length > 1) {
    setInterval(nextTopbar, 3500);
  }

  /* Mobile menu */
  const mobileOpen = document.getElementById("mobileMenuOpen");
  const mobileClose = document.getElementById("mobileMenuClose");
  const mobileMenu = document.getElementById("mobileMenu");
  const mobileOverlay = document.getElementById("mobileOverlay");
  const mobileMainList = document.getElementById("mobileMainList");
  const mobileSubMenu = document.getElementById("mobileSubMenu");
  const mobileSubTitle = document.getElementById("mobileSubTitle");
  const mobileSubBack = document.getElementById("mobileSubBack");
  const mobileSubItems = document.getElementById("mobileSubItems");

  function openMobileMenu() {
    mobileOverlay?.classList.remove("hidden");
    mobileMenu?.classList.remove("-translate-x-full");
  }

  function closeMobileMenu() {
    mobileOverlay?.classList.add("hidden");
    mobileMenu?.classList.add("-translate-x-full");
    mobileSubMenu?.classList.add("hidden");
    mobileMainList?.classList.remove("hidden");
  }

  mobileOpen?.addEventListener("click", openMobileMenu);
  mobileClose?.addEventListener("click", closeMobileMenu);
  mobileOverlay?.addEventListener("click", closeMobileMenu);

  const menuItems = window.MEZIVA_MENU_ITEMS || [];

  function getChildren(parentId) {
    return menuItems.filter((item) => Number(item.parent) === Number(parentId));
  }

  document.querySelectorAll(".meziva-mobile-sub-open").forEach((btn) => {
    btn.addEventListener("click", function () {
      const id = btn.dataset.menuId;
      const title = btn.dataset.title;
      const children = getChildren(id);

      if (!mobileSubTitle || !mobileSubItems) return;

      mobileSubTitle.textContent = title || "";
      mobileSubItems.innerHTML = "";

      const parent = menuItems.find((item) => Number(item.id) === Number(id));

      const viewAll = document.createElement("a");
      viewAll.className = "block";
      viewAll.href = parent?.url || "#";
      viewAll.textContent = "View all";
      mobileSubItems.appendChild(viewAll);

      children.forEach((item) => {
        const link = document.createElement("a");
        link.className = "block";
        link.href = item.url;
        link.textContent = item.title;
        mobileSubItems.appendChild(link);
      });

      mobileMainList?.classList.add("hidden");
      mobileSubMenu?.classList.remove("hidden");
    });
  });

  mobileSubBack?.addEventListener("click", function () {
    mobileSubMenu?.classList.add("hidden");
    mobileMainList?.classList.remove("hidden");
  });

  /* Mega menu */
  const megaMenu = document.getElementById("megaMenu");
  const megaColOne = document.getElementById("megaColOne");
  const megaColTwo = document.getElementById("megaColTwo");
  const megaColOneTitle = document.getElementById("megaColOneTitle");

  document.querySelectorAll(".meziva-mega-trigger").forEach((btn) => {
    btn.addEventListener("mouseenter", function () {
      const id = btn.dataset.menuId;
      const title = btn.textContent.replace("⌄", "").trim();
      const children = getChildren(id);

      if (!megaColOne || !megaColTwo) return;

      megaColOne.innerHTML = "";
      megaColTwo.innerHTML = "";

      if (megaColOneTitle) {
        megaColOneTitle.textContent = title;
      }

      children.forEach((item, index) => {
        const li = document.createElement("li");
        li.innerHTML = `<a class="hover:text-[#93aa52]" href="${item.url}">${item.title}</a>`;

        if (index < Math.ceil(children.length / 2)) {
          megaColOne.appendChild(li);
        } else {
          megaColTwo.appendChild(li);
        }
      });

      if (!children.length) {
        megaColOne.innerHTML = '<li><a href="#">No menu items added</a></li>';
      }

      megaMenu?.classList.remove("hidden");
    });
  });

  megaMenu?.addEventListener("mouseleave", function () {
    megaMenu.classList.add("hidden");
  });

  document.querySelector("header")?.addEventListener("mouseleave", function () {
    setTimeout(function () {
      if (!megaMenu?.matches(":hover")) {
        megaMenu?.classList.add("hidden");
      }
    }, 150);
  });

  /* Dynamic Search */
  const searchOpen = document.getElementById("searchOpen");
  const searchClose = document.getElementById("searchClose");
  const searchOverlay = document.getElementById("searchOverlay");
  const searchDrawer = document.getElementById("searchDrawer");
  const searchInput = document.getElementById("searchInput");
  const searchClear = document.getElementById("searchClear");
  const trendingSearch = document.getElementById("trendingSearch");
  const resultsWrap = document.getElementById("searchResultsWrap");
  const resultsBox = document.getElementById("searchResults");
  const tabs = Array.from(document.querySelectorAll(".meziva-search-tab"));

  let searchTimer = null;
  let activeTab = "products";

  let searchData = {
    products: [],
    posts: [],
    collections: [],
    pages: [],
  };

  function openSearch() {
    searchOverlay?.classList.remove("hidden");
    searchDrawer?.classList.remove("hidden");

    setTimeout(function () {
      searchInput?.focus();
    }, 100);
  }

  function updateTabs() {
    tabs.forEach((tab) => {
      const isActive = tab.dataset.tab === activeTab;

      tab.classList.toggle("text-black", isActive);
      tab.classList.toggle("text-gray-400", !isActive);
    });
  }

  function resetSearch() {
    if (searchInput) {
      searchInput.value = "";
    }

    activeTab = "products";

    searchData = {
      products: [],
      posts: [],
      collections: [],
      pages: [],
    };

    trendingSearch?.classList.remove("hidden");
    resultsWrap?.classList.add("hidden");
    searchClear?.classList.add("hidden");

    if (resultsBox) {
      resultsBox.innerHTML = "";
    }

    updateTabs();
  }

  function closeSearch() {
    searchOverlay?.classList.add("hidden");
    searchDrawer?.classList.add("hidden");
    resetSearch();
  }

  function renderResults() {
    if (!resultsBox) return;

    const items = searchData[activeTab] || [];
    resultsBox.innerHTML = "";

    if (!items.length) {
      resultsBox.innerHTML = `<p class="text-gray-500">No results found.</p>`;
      return;
    }

    if (activeTab === "products") {
      items.forEach((item) => {
        const link = document.createElement("a");
        link.href = item.url;
        link.className = "flex gap-5 items-center hover:bg-[#f7f3ec] p-2 rounded-xl";

        link.innerHTML = `
          <img src="${item.image || ""}" class="w-20 h-20 md:w-24 md:h-24 object-cover rounded-lg">
          <div>
            <h4 class="font-bold text-base md:text-lg">${item.title || ""}</h4>
            <div class="font-bold text-base md:text-lg">${item.price || ""}</div>
          </div>
        `;

        resultsBox.appendChild(link);
      });
    }

    if (activeTab === "posts") {
      items.forEach((item) => {
        const link = document.createElement("a");
        link.href = item.url;
        link.className = "flex gap-4 items-center hover:bg-[#f7f3ec] p-2 rounded-xl";

        link.innerHTML = `
          ${item.image ? `<img src="${item.image}" class="w-16 h-16 object-cover rounded-lg">` : ""}
          <div>
            <h4 class="font-bold text-base md:text-lg">${item.title || ""}</h4>
            <p class="text-sm text-gray-500">${item.date || ""}</p>
          </div>
        `;

        resultsBox.appendChild(link);
      });
    }

    if (activeTab === "collections") {
      items.forEach((item) => {
        const link = document.createElement("a");
        link.href = item.url;
        link.className = "flex gap-4 items-center hover:bg-[#f7f3ec] p-2 rounded-xl";

        link.innerHTML = `
          <img src="${item.image || ""}" class="w-16 h-16 object-cover rounded-lg">
          <div>
            <h4 class="font-bold text-base md:text-lg">${item.title || ""}</h4>
            <p class="text-sm text-gray-500">${item.count || 0} products</p>
          </div>
        `;

        resultsBox.appendChild(link);
      });
    }

    if (activeTab === "pages") {
      items.forEach((item) => {
        const link = document.createElement("a");
        link.href = item.url;
        link.className = "block hover:bg-[#f7f3ec] p-3 rounded-xl font-bold";
        link.textContent = item.title || "";
        resultsBox.appendChild(link);
      });
    }
  }

  function runSearch(keyword) {
    if (!keyword) {
      resetSearch();
      return;
    }

    searchClear?.classList.remove("hidden");
    trendingSearch?.classList.add("hidden");
    resultsWrap?.classList.remove("hidden");

    if (resultsBox) {
      resultsBox.innerHTML = `<p class="text-gray-500">Searching...</p>`;
    }

    fetch(`${meziva_ajax.ajax_url}?action=meziva_product_search&keyword=${encodeURIComponent(keyword)}`)
      .then((res) => res.json())
      .then((data) => {
        if (!data.success) {
          if (resultsBox) resultsBox.innerHTML = `<p>Something went wrong.</p>`;
          return;
        }

        if (Array.isArray(data.data)) {
          searchData = {
            products: data.data,
            posts: [],
            collections: [],
            pages: [],
          };
        } else {
          searchData = {
            products: data.data.products || [],
            posts: data.data.posts || [],
            collections: data.data.collections || [],
            pages: data.data.pages || [],
          };
        }

        activeTab = "products";
        updateTabs();
        renderResults();
      })
      .catch(() => {
        if (resultsBox) {
          resultsBox.innerHTML = `<p>Something went wrong.</p>`;
        }
      });
  }

  searchOpen?.addEventListener("click", openSearch);
  searchClose?.addEventListener("click", closeSearch);
  searchOverlay?.addEventListener("click", closeSearch);

  searchClear?.addEventListener("click", function () {
    resetSearch();
    searchInput?.focus();
  });

  searchInput?.addEventListener("input", function () {
    const keyword = this.value.trim();

    clearTimeout(searchTimer);

    if (!keyword) {
      resetSearch();
      return;
    }

    searchTimer = setTimeout(function () {
      runSearch(keyword);
    }, 350);
  });

  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      activeTab = this.dataset.tab;
      updateTabs();
      renderResults();
    });
  });

  document.querySelectorAll(".meziva-trending-item").forEach((item) => {
    item.addEventListener("click", function () {
      const keyword = this.dataset.keyword || this.textContent.trim();

      if (searchInput) {
        searchInput.value = keyword;
      }

      runSearch(keyword);
    });
  });

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      closeSearch();
    }
  });
});

/* Hero Slider */
document.addEventListener("DOMContentLoaded", function () {
  const slider = document.getElementById("homeHeroSlider");
  const prev = document.getElementById("homeHeroPrev");
  const next = document.getElementById("homeHeroNext");
  const dotsWrap = document.getElementById("homeHeroDots");

  if (!slider || !dotsWrap) return;

  const slides = Array.from(slider.children);
  let current = 0;
  let timer = null;

  if (slides.length <= 1) {
    prev?.classList.add("hidden");
    next?.classList.add("hidden");
    dotsWrap.classList.add("hidden");
    return;
  }

  slides.forEach((_, index) => {
    const dot = document.createElement("button");
    dot.type = "button";
    dot.className = "w-2.5 h-2.5 rounded-full bg-white/70 border border-white";

    dot.addEventListener("click", function () {
      goToSlide(index);
      restartAuto();
    });

    dotsWrap.appendChild(dot);
  });

  const dots = Array.from(dotsWrap.children);

  function updateSlider() {
    slider.style.transform = `translateX(-${current * 100}%)`;

    dots.forEach((dot, index) => {
      dot.className =
        index === current
          ? "w-6 h-2.5 rounded-full bg-white border border-white transition-all"
          : "w-2.5 h-2.5 rounded-full bg-white/70 border border-white transition-all";
    });
  }

  function goToSlide(index) {
    current = index;
    updateSlider();
  }

  function nextSlide() {
    current = (current + 1) % slides.length;
    updateSlider();
  }

  function prevSlide() {
    current = (current - 1 + slides.length) % slides.length;
    updateSlider();
  }

  function startAuto() {
    timer = setInterval(nextSlide, 4000);
  }

  function restartAuto() {
    clearInterval(timer);
    startAuto();
  }

  next?.addEventListener("click", function () {
    nextSlide();
    restartAuto();
  });

  prev?.addEventListener("click", function () {
    prevSlide();
    restartAuto();
  });

  updateSlider();
  startAuto();
});

/* Home Bestseller */
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".meziva-add-to-cart").forEach((btn) => {
    btn.addEventListener("click", function () {
      const productId = this.dataset.productId;
      const originalText = this.textContent;

      this.textContent = "Adding...";
      this.disabled = true;

      const formData = new FormData();
      formData.append("action", "meziva_add_to_cart");
      formData.append("product_id", productId);

      fetch(meziva_ajax.ajax_url, {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          this.textContent = data.success ? "Added ✓" : "Try Again";

          setTimeout(() => {
            this.textContent = originalText;
            this.disabled = false;
          }, 1500);
        })
        .catch(() => {
          this.textContent = "Try Again";
          this.disabled = false;
        });
    });
  });

  const bestsellerSlider = document.getElementById("bestsellerSlider");
  const bestsellerPrev = document.getElementById("bestsellerPrev");
  const bestsellerNext = document.getElementById("bestsellerNext");

  if (bestsellerSlider && bestsellerPrev && bestsellerNext) {
    bestsellerNext.addEventListener("click", function () {
      bestsellerSlider.scrollBy({
        left: bestsellerSlider.clientWidth,
        behavior: "smooth",
      });
    });

    bestsellerPrev.addEventListener("click", function () {
      bestsellerSlider.scrollBy({
        left: -bestsellerSlider.clientWidth,
        behavior: "smooth",
      });
    });
  }
});