(function () {
  "use strict";

  // ── Sidebar ────────────────────────────────────────────────────
  function initSidebar() {
    var sidebar = document.querySelector("[data-sidebar]");
    var toggle = document.querySelector("[data-sidebar-toggle]");
    var overlay = document.querySelector("[data-sidebar-overlay]");
    var mobileToggle = document.querySelector("[data-mobile-menu-toggle]");

    if (!sidebar) return;

    function getState() {
      return sidebar.getAttribute("data-collapsed") === "true";
    }

    function setState(collapsed) {
      sidebar.setAttribute("data-collapsed", String(collapsed));
      document.body.setAttribute("data-sidebar-state", collapsed ? "collapsed" : "expanded");
      try { localStorage.setItem("nh_sidebar", collapsed ? "collapsed" : "expanded"); } catch(e) {}
    }

    try {
      var saved = localStorage.getItem("nh_sidebar");
      if (saved === "collapsed" && window.innerWidth >= 768) {
        setState(true);
      }
    } catch(e) {}

    if (toggle) {
      toggle.addEventListener("click", function (e) {
        e.stopPropagation();
        setState(!getState());
      });
    }

    if (mobileToggle) {
      mobileToggle.addEventListener("click", function () {
        sidebar.classList.toggle("is-open");
        if (overlay) overlay.classList.toggle("is-visible");
        document.body.classList.toggle("menu-open");
      });
    }

    if (overlay) {
      overlay.addEventListener("click", function () {
        sidebar.classList.remove("is-open");
        overlay.classList.remove("is-visible");
        document.body.classList.remove("menu-open");
      });
    }

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && sidebar.classList.contains("is-open")) {
        sidebar.classList.remove("is-open");
        if (overlay) overlay.classList.remove("is-visible");
        document.body.classList.remove("menu-open");
      }
    });

    var resizeTimer;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        if (window.innerWidth >= 768) {
          sidebar.classList.remove("is-open");
          if (overlay) overlay.classList.remove("is-visible");
        }
      }, 150);
    });
  }

  // ── Theme Toggle ───────────────────────────────────────────────
  function initThemeToggle() {
    var toggle = document.querySelector("[data-theme-toggle]");
    if (!toggle) return;

    function getTheme() {
      return document.documentElement.getAttribute("data-theme") || "light";
    }

    function setTheme(theme) {
      if (theme === "dark") {
        document.documentElement.setAttribute("data-theme", "dark");
      } else {
        document.documentElement.removeAttribute("data-theme");
      }
      try { localStorage.setItem("nh_theme", theme); } catch(e) {}
    }

    try {
      var saved = localStorage.getItem("nh_theme");
      if (saved === "dark") setTheme("dark");
    } catch(e) {}

    toggle.addEventListener("click", function () {
      var current = getTheme();
      setTheme(current === "dark" ? "light" : "dark");
    });
  }

  // ── Search ─────────────────────────────────────────────────────
  function initSearch() {
    var searchInput = document.querySelector(".topbar__search-input");
    if (!searchInput) return;

    document.addEventListener("keydown", function (e) {
      if ((e.ctrlKey || e.metaKey) && e.key === "k") {
        e.preventDefault();
        searchInput.focus();
      }
    });

    searchInput.addEventListener("keydown", function (e) {
      if (e.key === "Escape") {
        searchInput.blur();
      }
    });
  }

  // ── Profile Dropdown ───────────────────────────────────────────
  function initProfileDropdown() {
    var container = document.querySelector("[data-profile-toggle]");
    if (!container) return;

    var btn = container.querySelector(".topbar__profile-btn");
    var menu = container.querySelector("[data-profile-menu]");
    if (!btn || !menu) return;

    btn.addEventListener("click", function (e) {
      e.stopPropagation();
      var isOpen = !menu.hasAttribute("hidden");
      menu.hidden = isOpen;
      btn.setAttribute("aria-expanded", String(!isOpen));
    });

    document.addEventListener("click", function (e) {
      if (!menu.hidden && !container.contains(e.target)) {
        menu.hidden = true;
        btn.setAttribute("aria-expanded", "false");
      }
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !menu.hidden) {
        menu.hidden = true;
        btn.setAttribute("aria-expanded", "false");
        btn.focus();
      }
    });
  }

  // ── Notifications Panel ────────────────────────────────────────
  function initNotifications() {
    var toggle = document.querySelector("[data-notif-toggle]");
    var badge = document.querySelector("[data-notif-badge]");
    if (!toggle) return;

    var panel = null;
    var isOpen = false;

    function fetchNotifications() {
      var url = toggle.getAttribute("data-url") || "/api/notifications/recent";
      var xhr = new XMLHttpRequest();
      xhr.open("GET", url);
      xhr.setRequestHeader("Accept", "application/json");
      xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

      xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
          try {
            var data = JSON.parse(xhr.responseText);
            var items = Array.isArray(data) ? data : (data.notifications || []);
            renderPanel(items);
          } catch(e) {
            renderFallback();
          }
        } else {
          renderFallback();
        }
      };
      xhr.onerror = function () { renderFallback(); };
      xhr.send();
    }

    function renderPanel(notifications) {
      closePanel();
      panel = document.createElement("div");
      panel.className = "notif-panel";
      panel.setAttribute("data-notif-panel", "");

      var header = document.createElement("div");
      header.className = "notif-panel__header";
      header.innerHTML = '<h4>Notifications</h4><span class="counter-badge">'+notifications.length+'</span>';
      panel.appendChild(header);

      var list = document.createElement("div");
      list.className = "notif-panel__list";
      list.setAttribute("data-notif-list", "");

      if (notifications.length === 0) {
        var empty = document.createElement("p");
        empty.className = "notif-empty";
        empty.textContent = "You're all caught up.";
        list.appendChild(empty);
      } else {
        notifications.forEach(function (n) {
          var isUnread = n.unread === true || n.read_at === null || n.read_at === undefined;
          var item = document.createElement("div");
          item.className = "notif-item " + (isUnread ? "is-unread" : "is-read");

          var dot = document.createElement("span");
          dot.className = "notif-item__dot";
          dot.setAttribute("aria-hidden", "true");

          var body = document.createElement("div");
          body.className = "notif-item__body";

          var text = document.createElement("p");
          text.className = "notif-item__text";
          text.textContent = n.title || n.text || n.message || "";

          var msg = document.createElement("p");
          msg.style.cssText = "font-size:0.72rem;color:var(--color-text-muted);margin:0.1rem 0 0;";
          msg.textContent = n.message || "";

          var time = document.createElement("p");
          time.className = "notif-item__time";
          var rawTime = n.created_at || n.time || "";
          time.textContent = typeof rawTime === 'string' ? rawTime : (rawTime ? new Date(rawTime).toLocaleDateString() : "");

          body.appendChild(text);
          if (n.message) body.appendChild(msg);
          body.appendChild(time);
          item.appendChild(dot);
          item.appendChild(body);
          list.appendChild(item);
        });
      }

      panel.appendChild(list);

      var footer = document.createElement("div");
      footer.className = "notif-panel__footer";
      footer.innerHTML = '<a href="/notifications">View all notifications</a>';
      panel.appendChild(footer);

      toggle.appendChild(panel);
      isOpen = true;

      if (badge) badge.style.display = "none";

      setTimeout(function () {
        document.addEventListener("click", closeHandler, false);
      }, 0);
    }

    function renderFallback() {
      closePanel();
      panel = document.createElement("div");
      panel.className = "notif-panel";
      panel.setAttribute("data-notif-panel", "");

      var header = document.createElement("div");
      header.className = "notif-panel__header";
      header.innerHTML = '<h4>Notifications</h4>';
      panel.appendChild(header);

      var list = document.createElement("div");
      list.className = "notif-panel__list";
      var empty = document.createElement("p");
      empty.className = "notif-empty";
      empty.textContent = "No notifications yet.";
      list.appendChild(empty);
      panel.appendChild(list);

      toggle.appendChild(panel);
      isOpen = true;

      setTimeout(function () {
        document.addEventListener("click", closeHandler, false);
      }, 0);
    }

    function closeHandler(e) {
      if (panel && !toggle.contains(e.target)) {
        closePanel();
      }
    }

    function closePanel() {
      if (panel && panel.parentNode) {
        panel.parentNode.removeChild(panel);
      }
      panel = null;
      isOpen = false;
      document.removeEventListener("click", closeHandler, false);
    }

    toggle.addEventListener("click", function (e) {
      e.stopPropagation();
      if (isOpen) {
        closePanel();
      } else {
        fetchNotifications();
      }
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && isOpen) {
        closePanel();
      }
    });
  }

  // ── Auto Theme from System ────────────────────────────────────
  function initSystemTheme() {
    try {
      var saved = localStorage.getItem("nh_theme");
      if (!saved) {
        var prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        if (prefersDark) {
          document.documentElement.setAttribute("data-theme", "dark");
        }
      }
    } catch(e) {}
  }

  // ── Active Nav Link ────────────────────────────────────────────
  function setActiveNav(pageKey) {
    var links = document.querySelectorAll("[data-nav-link]");
    links.forEach(function (link) {
      var isMatch = link.getAttribute("data-nav-link") === pageKey;
      if (isMatch) {
        link.setAttribute("aria-current", "page");
        link.classList.add("sidebar__link--active");
      } else {
        link.removeAttribute("aria-current");
        link.classList.remove("sidebar__link--active");
      }
    });
  }

  // ── Form Validation ────────────────────────────────────────────
  function validateField(field, fieldRules, form) {
    var value = (field.value || "").trim();
    var message = "";
    var isCheckbox = field.type === "checkbox";
    var isRadio = field.type === "radio";
    var fieldSet = isRadio ? form.querySelectorAll('[name="' + field.name + '"]') : null;
    var isChecked = isCheckbox ? field.checked : isRadio ? Array.prototype.some.call(fieldSet, function (opt) {
      return opt.checked;
    }) : Boolean(value);

    if (fieldRules.required && !isChecked) {
      message = "This field is required.";
    } else if (fieldRules.email && value) {
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) message = "Enter a valid email address.";
    } else if (fieldRules.minLength && value.length < fieldRules.minLength) {
      message = "Must be at least " + fieldRules.minLength + " characters.";
    }

    var errorEl = form.querySelector('[data-error-for="' + field.name + '"]');
    var group = field.closest(".form-group") || field.closest("[data-field-group]");

    if (errorEl) errorEl.textContent = message;
    if (isRadio && fieldSet) {
      Array.prototype.forEach.call(fieldSet, function (opt) {
        opt.setAttribute("aria-invalid", String(Boolean(message)));
      });
    } else {
      field.setAttribute("aria-invalid", String(Boolean(message)));
    }
    if (group) group.classList.toggle("has-error", Boolean(message));

    return !message;
  }

  function inferRulesFromForm(form) {
    var rules = {};
    var fields = form.querySelectorAll("input[name], select[name], textarea[name]");

    fields.forEach(function (field) {
      var rule = {};
      if (field.hasAttribute("required")) rule.required = true;
      if (field.type === "email") rule.email = true;
      if (field.minLength && Number(field.minLength) > 0) rule.minLength = Number(field.minLength);
      if (Object.keys(rule).length) rules[field.name] = rule;
    });

    return rules;
  }

  function initFormValidation(formSelector, rules) {
    var forms = document.querySelectorAll(formSelector);
    forms.forEach(function (form) {
      var validationRules = rules || inferRulesFromForm(form);
      var fields = form.querySelectorAll("input[name], select[name], textarea[name]");

      fields.forEach(function (field) {
        field.addEventListener("blur", function () {
          if (validationRules[field.name]) validateField(field, validationRules[field.name], form);
        });
        field.addEventListener("input", function () {
          if (validationRules[field.name]) validateField(field, validationRules[field.name], form);
        });
      });

      form.addEventListener("submit", function (event) {
        var valid = true;
        Object.keys(validationRules).forEach(function (name) {
          var f = form.querySelector('[name="' + name + '"]');
          if (!f) return;
          valid = validateField(f, validationRules[name], form) && valid;
        });
        if (!valid) event.preventDefault();
      });
    });
  }

  // ── Modal helpers ──────────────────────────────────────────────
  function openModal(modalId) {
    var modal = document.getElementById(modalId);
    if (!modal) return;
    modal.hidden = false;
    document.body.style.overflow = "hidden";
  }

  function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    if (!modal) return;
    modal.hidden = true;
    document.body.style.overflow = "";
  }

  function initModals() {
    document.addEventListener("click", function (e) {
      var toggleBtn = e.target.closest("[data-modal-open]");
      if (toggleBtn) {
        e.preventDefault();
        openModal(toggleBtn.getAttribute("data-modal-open"));
      }

      var closeBtn = e.target.closest("[data-modal-close]");
      if (closeBtn) {
        e.preventDefault();
        var modal = closeBtn.closest(".modal-overlay");
        if (modal) closeModal(modal.id);
      }

      var overlay = e.target.closest(".modal-overlay");
      if (overlay && e.target === overlay) {
        closeModal(overlay.id);
      }
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") {
        var openModal = document.querySelector('.modal-overlay:not([hidden])');
        if (openModal) closeModal(openModal.id);
      }
    });
  }

  // ── Dismiss alerts ────────────────────────────────────────────
  function initAlerts() {
    document.addEventListener("click", function (e) {
      var closeBtn = e.target.closest(".alert__close");
      if (closeBtn) {
        var alert = closeBtn.closest(".alert");
        if (alert) {
          alert.style.transition = "opacity 0.2s ease, transform 0.2s ease";
          alert.style.opacity = "0";
          alert.style.transform = "translateY(-8px)";
          setTimeout(function () { if (alert.parentNode) alert.parentNode.removeChild(alert); }, 200);
        }
      }
    });

    document.querySelectorAll(".alert").forEach(function (alert) {
      setTimeout(function () {
        if (alert.parentNode) {
          alert.style.transition = "opacity 0.3s ease";
          alert.style.opacity = "0";
          setTimeout(function () { if (alert.parentNode) alert.parentNode.removeChild(alert); }, 300);
        }
      }, 5000);
    });
  }

  // ── Animate stat cards on scroll ──────────────────────────────
  function initScrollAnimations() {
    if (!window.IntersectionObserver) return;
    var cards = document.querySelectorAll(".stat-card, .card");
    if (cards.length === 0) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.opacity = "1";
          entry.target.style.transform = "translateY(0)";
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    cards.forEach(function (card) {
      card.style.opacity = "0";
      card.style.transform = "translateY(12px)";
      card.style.transition = "opacity 0.4s ease, transform 0.4s ease";
      observer.observe(card);
    });
  }

  // ── Init ───────────────────────────────────────────────────────
  document.addEventListener("DOMContentLoaded", function () {
    initSystemTheme();
    initSidebar();
    initThemeToggle();
    initSearch();
    initProfileDropdown();
    initNotifications();
    setActiveNav(document.body.dataset.page || "");
    initFormValidation("form[data-validate]");
    initModals();
    initAlerts();
    initScrollAnimations();
  });

  // ── Password Utilities ──────────────────────────────────────────
  window.setupPasswordToggles = function () {
    document.querySelectorAll("[data-pw-toggle]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var wrapper = btn.closest(".pw-wrapper");
        var input = wrapper.querySelector("[data-pw-field]");
        if (!input) return;
        var isVisible = input.type === "text";
        input.type = isVisible ? "password" : "text";
        btn.classList.toggle("is-visible", !isVisible);
        btn.setAttribute("aria-label", isVisible ? "Show password" : "Hide password");
      });
    });
  };

  window.setupPasswordStrength = function () {
    var input = document.querySelector("[data-pw-strength]");
    var meter = document.querySelector("[data-pw-strength-meter]");
    if (!input || !meter) return;

    input.addEventListener("input", function () {
      var val = input.value;
      if (val.length === 0) {
        meter.classList.remove("is-active");
        return;
      }
      meter.classList.add("is-active");
      var score = 0;
      if (val.length >= 8) score++;
      if (val.length >= 12) score++;
      if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
      if (/\d/.test(val)) score++;
      if (/[^a-zA-Z0-9]/.test(val)) score++;

      var idx = Math.min(score, 3);
      var levels = ["weak", "medium", "strong", "very-strong"];
      var labels = ["Weak", "Fair", "Good", "Strong"];
      meter.className = "pw-strength is-active pw-strength--" + levels[idx];
      meter.querySelector(".pw-strength__label").textContent = labels[idx];
    });
  };

  window.setupPasswordMatch = function () {
    var confirmInput = document.querySelector("[data-pw-match]");
    var indicator = document.querySelector("[data-pw-match-indicator]");
    if (!confirmInput || !indicator) return;

    var targetId = confirmInput.getAttribute("data-pw-match");
    var targetInput = document.getElementById(targetId);
    if (!targetInput) return;

    function check() {
      var confirmVal = confirmInput.value;
      var targetVal = targetInput.value;
      if (confirmVal.length === 0) {
        indicator.classList.remove("is-active");
        return;
      }
      indicator.classList.add("is-active");
      if (confirmVal === targetVal && confirmVal.length > 0) {
        indicator.className = "pw-match is-active pw-match--match";
        indicator.querySelector(".pw-match__text").textContent = "Passwords match";
      } else {
        indicator.className = "pw-match is-active pw-match--no-match";
        indicator.querySelector(".pw-match__text").textContent = "Passwords do not match";
      }
    }

    confirmInput.addEventListener("input", check);
    targetInput.addEventListener("input", check);
  };

  // ── Public API ─────────────────────────────────────────────────
  window.NeuroHaven = {
    initSidebar: initSidebar,
    initThemeToggle: initThemeToggle,
    setActiveNav: setActiveNav,
    openModal: openModal,
    closeModal: closeModal,
    initFormValidation: initFormValidation,
    setupPasswordToggles: window.setupPasswordToggles,
    setupPasswordStrength: window.setupPasswordStrength,
    setupPasswordMatch: window.setupPasswordMatch
  };

})();
