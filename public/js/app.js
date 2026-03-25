(function () {
  "use strict";

  function initMobileNav() {
    var toggle = document.querySelector("[data-nav-toggle]");
    var mobileMenu = document.querySelector("[data-mobile-menu]");

    if (!toggle || !mobileMenu) return;

    function setMenuState(isOpen) {
      toggle.setAttribute("aria-expanded", String(isOpen));
      mobileMenu.classList.toggle("is-open", isOpen);
      document.body.classList.toggle("menu-open", isOpen);
    }

    setMenuState(false);

    toggle.addEventListener("click", function () {
      var next = toggle.getAttribute("aria-expanded") !== "true";
      setMenuState(next);
    });

    document.addEventListener("click", function (event) {
      if (toggle.getAttribute("aria-expanded") !== "true") return;
      if (toggle.contains(event.target) || mobileMenu.contains(event.target)) return;
      setMenuState(false);
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") setMenuState(false);
    });
  }

  function validateField(field, fieldRules, form) {
    var value = (field.value || "").trim();
    var message = "";
    var isCheckbox = field.type === "checkbox";
    var isRadio = field.type === "radio";
    var fieldSet = isRadio ? form.querySelectorAll('[name="' + field.name + '"]') : null;
    var isChecked = isCheckbox ? field.checked : isRadio ? Array.prototype.some.call(fieldSet, function (option) {
      return option.checked;
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
    var group = field.closest(".input-group") || field.closest("[data-field-group]");

    if (errorEl) errorEl.textContent = message;
    if (isRadio && fieldSet) {
      Array.prototype.forEach.call(fieldSet, function (option) {
        option.setAttribute("aria-invalid", String(Boolean(message)));
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
      var fieldRule = {};
      if (field.hasAttribute("required")) fieldRule.required = true;
      if (field.type === "email") fieldRule.email = true;
      if (field.minLength && Number(field.minLength) > 0) fieldRule.minLength = Number(field.minLength);
      if (Object.keys(fieldRule).length) rules[field.name] = fieldRule;
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
        var isFormValid = true;

        Object.keys(validationRules).forEach(function (name) {
          var field = form.querySelector('[name="' + name + '"]');
          if (!field) return;
          isFormValid = validateField(field, validationRules[name], form) && isFormValid;
        });

        if (!isFormValid) {
          event.preventDefault();
        }
      });
    });
  }

  function setActiveNav(pageKey) {
    var links = document.querySelectorAll("[data-nav-link]");
    links.forEach(function (link) {
      var isMatch = link.getAttribute("data-nav-link") === pageKey;
      if (isMatch) {
        link.setAttribute("aria-current", "page");
      } else {
        link.removeAttribute("aria-current");
      }
    });
  }

  function initNotifications() {
    var toggle = document.querySelector("[data-notif-toggle]");
    var panel  = document.querySelector("[data-notif-panel]");
    var list   = document.querySelector("[data-notif-list]");
    var badge  = document.querySelector("[data-notif-badge]");
    var clear  = document.querySelector("[data-notif-clear]");

    if (!toggle || !panel) return;

    var notifications = [
      { id: 1, text: "You're on a 12-day streak — keep it going!", time: "Just now",    unread: true  },
      { id: 2, text: "New space added: Desert Sunrise.",           time: "2 hours ago", unread: true  },
      { id: 3, text: "Your weekly mood report is ready.",          time: "Yesterday",   unread: true  },
      { id: 4, text: "Session reminder: you haven't checked in today.", time: "2 days ago", unread: false }
    ];

    function unreadCount() {
      return notifications.filter(function (n) { return n.unread; }).length;
    }

    function renderBadge() {
      var count = unreadCount();
      badge.classList.toggle("is-visible", count > 0);
      toggle.setAttribute("aria-label", count > 0 ? "Notifications (" + count + " unread)" : "Notifications");
    }

    function renderList() {
      if (notifications.length === 0) {
        list.innerHTML = "<li class=\"notif-empty\">You're all caught up.</li>";
        return;
      }
      list.innerHTML = notifications.map(function (n) {
        return [
          "<li class=\"notif-item " + (n.unread ? "is-unread" : "is-read") + "\" data-notif-id=\"" + n.id + "\">",
          "  <span class=\"notif-item__dot\" aria-hidden=\"true\"></span>",
          "  <div class=\"notif-item__body\">",
          "    <p class=\"notif-item__text\">" + n.text + "</p>",
          "    <p class=\"notif-item__time\">" + n.time + "</p>",
          "  </div>",
          "</li>"
        ].join("");
      }).join("");
    }

    function openPanel() {
      panel.hidden = false;
      toggle.setAttribute("aria-expanded", "true");
      // mark all as read when opened
      notifications.forEach(function (n) { n.unread = false; });
      renderBadge();
      renderList();
    }

    function closePanel() {
      panel.hidden = true;
      toggle.setAttribute("aria-expanded", "false");
    }

    function togglePanel() {
      if (panel.hidden) { openPanel(); } else { closePanel(); }
    }

    toggle.addEventListener("click", function (e) {
      e.stopPropagation();
      togglePanel();
    });

    if (clear) {
      clear.addEventListener("click", function () {
        notifications.forEach(function (n) { n.unread = false; });
        renderBadge();
        renderList();
      });
    }

    document.addEventListener("click", function (e) {
      if (!panel.hidden && !panel.contains(e.target) && !toggle.contains(e.target)) {
        closePanel();
      }
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !panel.hidden) closePanel();
    });

    // init
    renderBadge();
    renderList();
  }

  function initAutoValidation() {
    initFormValidation("form[data-validate]");
  }

  document.addEventListener("DOMContentLoaded", function () {
    initMobileNav();
    setActiveNav(document.body.dataset.page || "");
    initAutoValidation();
    initNotifications();
    initLogout();
    initNavAuth();
  });

  // ── Auth helpers ────────────────────────────────────────────────────────────
  var AUTH_KEY = 'nh_user';

  function getUser() {
    try { return JSON.parse(localStorage.getItem(AUTH_KEY)); } catch (e) { return null; }
  }

  function setUser(data) {
    localStorage.setItem(AUTH_KEY, JSON.stringify(data));
  }

  function clearUser() {
    localStorage.removeItem(AUTH_KEY);
    localStorage.removeItem('nh_onboarded');
  }

  // Show a simple modal asking user to log in, with option to dismiss and stay
  function requireAuth() {
    if (getUser()) return true;
    // Not logged in — redirect to login
    window.location.href = 'login.html';
    return false;
  }

  function redirectIfAuthed() {
    if (getUser()) {
      window.location.replace('dashboard.html');
      return true;
    }
    return false;
  }

  function initLogout() {
    document.addEventListener('click', function (e) {
      if (e.target.closest('[data-logout]')) {
        clearUser();
        window.location.href = 'login.html';
      }
    });
  }

  // Update top nav based on auth state
  function initNavAuth() {
    var user = getUser();
    var loginLink = document.querySelector('[data-nav-link="login"]');
    var getStartedBtn = document.querySelector('.site-nav .btn-primary[href="register.html"]');
    if (user) {
      // Hide login link, replace Get Started with Logout
      if (loginLink) loginLink.style.display = 'none';
      if (getStartedBtn) {
        getStartedBtn.textContent = 'Log Out';
        getStartedBtn.removeAttribute('href');
        getStartedBtn.setAttribute('data-logout', '');
        getStartedBtn.classList.remove('btn-primary');
        getStartedBtn.classList.add('btn-ghost');
      }
    }
  }

  window.NeuroHaven = {
    initMobileNav: initMobileNav,
    initFormValidation: initFormValidation,
    setActiveNav: setActiveNav,
    getUser: getUser,
    setUser: setUser,
    clearUser: clearUser,
    requireAuth: requireAuth,
    redirectIfAuthed: redirectIfAuthed
  };

})();
