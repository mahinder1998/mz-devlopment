document.addEventListener("DOMContentLoaded", function () {
  const mobileOpen = document.getElementById("mobileMenuOpen");
  const mobileClose = document.getElementById("mobileMenuClose");
  const mobileMenu = document.getElementById("mobileMenu");
  const mobileOverlay = document.getElementById("mobileOverlay");

  const mobileMainList = document.getElementById("mobileMainList");
  const mobileSubMenu = document.getElementById("mobileSubMenu");
  const mobileSubTitle = document.getElementById("mobileSubTitle");
  const mobileSubBack = document.getElementById("mobileSubBack");

  function openMobileMenu() {
    mobileOverlay.classList.remove("hidden");
    mobileMenu.classList.remove("-translate-x-full");
  }

  function closeMobileMenu() {
    mobileOverlay.classList.add("hidden");
    mobileMenu.classList.add("-translate-x-full");
  }

  mobileOpen?.addEventListener("click", openMobileMenu);
  mobileClose?.addEventListener("click", closeMobileMenu);
  mobileOverlay?.addEventListener("click", closeMobileMenu);

  document.querySelectorAll(".mobileSubOpen").forEach((btn) => {
    btn.addEventListener("click", () => {
      mobileSubTitle.textContent = btn.dataset.title;
      mobileMainList.classList.add("hidden");
      mobileSubMenu.classList.remove("hidden");
    });
  });

  mobileSubBack?.addEventListener("click", () => {
    mobileSubMenu.classList.add("hidden");
    mobileMainList.classList.remove("hidden");
  });

  const megaMenu = document.getElementById("megaMenu");
  const megaTriggers = document.querySelectorAll(".megaTrigger");

  megaTriggers.forEach((btn) => {
    btn.addEventListener("mouseenter", () => {
      megaMenu.classList.remove("hidden");
    });
  });

  megaMenu?.addEventListener("mouseleave", () => {
    megaMenu.classList.add("hidden");
  });

  document.querySelector("header")?.addEventListener("mouseleave", () => {
    setTimeout(() => {
      if (!megaMenu.matches(":hover")) {
        megaMenu.classList.add("hidden");
      }
    }, 200);
  });

  const searchOpen = document.getElementById("searchOpen");
  const searchClose = document.getElementById("searchClose");
  const searchOverlay = document.getElementById("searchOverlay");
  const searchDrawer = document.getElementById("searchDrawer");
  const searchInput = document.getElementById("searchInput");
  const searchClear = document.getElementById("searchClear");
  const trendingSearch = document.getElementById("trendingSearch");
  const resultsWrap = document.getElementById("searchResultsWrap");
  const resultsBox = document.getElementById("searchResults");

  function openSearch() {
    searchOverlay.classList.remove("hidden");
    searchDrawer.classList.remove("hidden");
    setTimeout(() => searchInput.focus(), 100);
  }

  function closeSearch() {
    searchOverlay.classList.add("hidden");
    searchDrawer.classList.add("hidden");
    searchInput.value = "";
    resetSearch();
  }

  function resetSearch() {
    trendingSearch.classList.remove("hidden");
    resultsWrap.classList.add("hidden");
    searchClear.classList.add("hidden");
    resultsBox.innerHTML = "";
  }

  searchOpen?.addEventListener("click", openSearch);
  searchClose?.addEventListener("click", closeSearch);
  searchOverlay?.addEventListener("click", closeSearch);

  searchClear?.addEventListener("click", () => {
    searchInput.value = "";
    resetSearch();
    searchInput.focus();
  });

  let timer;

  searchInput?.addEventListener("input", function () {
    const keyword = this.value.trim();

    clearTimeout(timer);

    if (!keyword) {
      resetSearch();
      return;
    }

    searchClear.classList.remove("hidden");
    trendingSearch.classList.add("hidden");
    resultsWrap.classList.remove("hidden");

    resultsBox.innerHTML = `<p class="text-gray-500">Searching...</p>`;

    timer = setTimeout(() => {
      fetch(`${meziva_ajax.ajax_url}?action=meziva_product_search&keyword=${encodeURIComponent(keyword)}`)
        .then((res) => res.json())
        .then((data) => {
          resultsBox.innerHTML = "";

          if (!data.success || data.data.length === 0) {
            resultsBox.innerHTML = `<p>No products found.</p>`;
            return;
          }

          data.data.forEach((item) => {
            const div = document.createElement("a");
            div.href = item.url;
            div.className = "flex gap-5 items-center";

            div.innerHTML = `
              <img src="${item.image}" class="w-24 h-24 object-cover">
              <div>
                <h4 class="font-bold text-lg">${item.title}</h4>
                <div class="font-bold text-lg">${item.price}</div>
              </div>
            `;

            resultsBox.appendChild(div);
          });
        });
    }, 350);
  });
});