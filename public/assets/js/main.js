(function () {
  "use strict";
  var root = document.documentElement;

  // Theme toggle (per-viewer convenience)
  var THEME_KEY = "mm-theme";
  try {
    var saved = localStorage.getItem(THEME_KEY);
    if (saved === "light" || saved === "dark") root.setAttribute("data-theme", saved);
  } catch (e) {}

  function currentTheme() {
    var t = root.getAttribute("data-theme");
    if (t === "light" || t === "dark") return t;
    return window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
  }

  var themeBtn = document.querySelector(".theme-toggle");
  if (themeBtn) {
    themeBtn.addEventListener("click", function () {
      var next = currentTheme() === "dark" ? "light" : "dark";
      root.setAttribute("data-theme", next);
      try { localStorage.setItem(THEME_KEY, next); } catch (e) {}
    });
  }

  // Mobile nav
  var toggle = document.querySelector(".nav-toggle");
  var links = document.getElementById("nav-links");
  if (toggle && links) {
    toggle.addEventListener("click", function () {
      var open = links.classList.toggle("open");
      toggle.setAttribute("aria-expanded", String(open));
      toggle.setAttribute("aria-label", open ? "بستن منو" : "باز کردن منو");
    });
    links.addEventListener("click", function (e) {
      if (e.target.closest("a")) {
        links.classList.remove("open");
        toggle.setAttribute("aria-expanded", "false");
      }
    });
  }

  // Header rule once the page scrolls
  var header = document.querySelector(".site-header");
  function onScroll() { if (header) header.classList.toggle("scrolled", window.scrollY > 8); }
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  // Active nav link
  var navAnchors = document.querySelectorAll('.nav-links a[href^="#"]');
  var map = {};
  navAnchors.forEach(function (a) { map[a.getAttribute("href").slice(1)] = a; });
  if ("IntersectionObserver" in window) {
    var so = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        var a = map[entry.target.id];
        if (a && entry.isIntersecting) {
          navAnchors.forEach(function (x) { x.classList.remove("active"); });
          a.classList.add("active");
        }
      });
    }, { rootMargin: "-45% 0px -50% 0px" });
    Object.keys(map).forEach(function (id) {
      var s = document.getElementById(id);
      if (s) so.observe(s);
    });
  }

  // Copy e-mail address
  var copyBtn = document.getElementById("copy-mail");
  if (copyBtn) {
    copyBtn.addEventListener("click", function () {
      var el = document.getElementById(copyBtn.getAttribute("data-target"));
      if (!el) return;
      var text = el.textContent.trim();
      function selectText() {
        var r = document.createRange(); r.selectNodeContents(el);
        var s = window.getSelection(); s.removeAllRanges(); s.addRange(r);
        copyBtn.textContent = "نشانی انتخاب شد";
      }
      try {
        navigator.clipboard.writeText(text).then(function () {
          copyBtn.textContent = "کپی شد";
          setTimeout(function () { copyBtn.textContent = "کپی نشانی"; }, 2000);
        }, selectText);
      } catch (e) { selectText(); }
    });
  }

  // Current Persian year
  var y = document.getElementById("year");
  if (y) {
    try {
      var py = new Intl.DateTimeFormat("fa-IR-u-ca-persian", { year: "numeric" }).format(new Date()).replace(/[^۰-۹]/g, "");
      if (py) y.textContent = py;
    } catch (e) {}
  }
})();
