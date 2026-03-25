(function () {
  'use strict';

  var PREFS_KEY = 'nh_notif_prefs';

  var INTERVALS = {
    low:    2 * 60 * 60 * 1000,  // every 2 hours
    medium: 60 * 60 * 1000,      // every hour
    high:   30 * 60 * 1000       // every 30 min
  };

  function getPrefs() {
    try { return JSON.parse(localStorage.getItem(PREFS_KEY)) || {}; } catch (e) { return {}; }
  }

  function savePrefs(prefs) {
    localStorage.setItem(PREFS_KEY, JSON.stringify(prefs));
  }

  function isSupported() {
    return 'Notification' in window && 'serviceWorker' in navigator;
  }

  function getPermission() {
    return Notification.permission; // 'default' | 'granted' | 'denied'
  }

  function requestPermission(callback) {
    if (!isSupported()) return callback('unsupported');
    Notification.requestPermission().then(function (result) {
      callback(result);
    });
  }

  function registerSW(callback) {
    navigator.serviceWorker.register('/sw.js').then(function (reg) {
      callback(null, reg);
    }).catch(function (err) {
      callback(err);
    });
  }

  function startNotifications(frequency) {
    if (!isSupported() || getPermission() !== 'granted') return;
    var intervalMs = INTERVALS[frequency] || INTERVALS.medium;
    navigator.serviceWorker.ready.then(function (reg) {
      if (reg.active) {
        reg.active.postMessage({ type: 'SCHEDULE_NOTIFICATIONS', intervalMs: intervalMs });
      }
    });
  }

  function stopNotifications() {
    navigator.serviceWorker.ready.then(function (reg) {
      if (reg.active) {
        reg.active.postMessage({ type: 'CANCEL_NOTIFICATIONS' });
      }
    });
  }

  // Called after login — if user has notifications enabled, start them
  function initOnLogin() {
    if (!isSupported()) return;
    var prefs = getPrefs();
    if (prefs.enabled && getPermission() === 'granted') {
      registerSW(function (err) {
        if (!err) startNotifications(prefs.frequency || 'medium');
      });
    }
  }

  // Show a one-time in-app prompt after login if permission not yet asked
  function maybePromptAfterLogin() {
    if (!isSupported()) return;
    if (getPermission() !== 'default') return; // already decided
    var prefs = getPrefs();
    if (prefs.promptDismissed) return;

    // Show after 3 seconds
    setTimeout(function () {
      var banner = document.createElement('div');
      banner.className = 'notif-prompt';
      banner.setAttribute('role', 'dialog');
      banner.setAttribute('aria-label', 'Enable notifications');
      banner.innerHTML = [
        '<div class="notif-prompt__icon" aria-hidden="true">',
        '  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">',
        '    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>',
        '    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>',
        '  </svg>',
        '</div>',
        '<div class="notif-prompt__copy">',
        '  <p class="notif-prompt__title">Stay connected to your wellness</p>',
        '  <p class="notif-prompt__sub">Get gentle check-ins and encouragement throughout your day.</p>',
        '</div>',
        '<div class="notif-prompt__actions">',
        '  <button class="btn btn-primary btn-sm" data-notif-allow>Allow</button>',
        '  <button class="btn btn-ghost btn-sm" data-notif-dismiss>Not now</button>',
        '</div>'
      ].join('');

      document.body.appendChild(banner);

      // Animate in
      requestAnimationFrame(function () { banner.classList.add('is-visible'); });

      banner.querySelector('[data-notif-allow]').addEventListener('click', function () {
        requestPermission(function (result) {
          if (result === 'granted') {
            var prefs = getPrefs();
            prefs.enabled = true;
            prefs.frequency = prefs.frequency || 'medium';
            savePrefs(prefs);
            registerSW(function (err) {
              if (!err) startNotifications(prefs.frequency);
            });
          }
          banner.classList.remove('is-visible');
          setTimeout(function () { banner.remove(); }, 300);
        });
      });

      banner.querySelector('[data-notif-dismiss]').addEventListener('click', function () {
        var prefs = getPrefs();
        prefs.promptDismissed = true;
        savePrefs(prefs);
        banner.classList.remove('is-visible');
        setTimeout(function () { banner.remove(); }, 300);
      });
    }, 3000);
  }

  window.NHNotifications = {
    isSupported: isSupported,
    getPermission: getPermission,
    requestPermission: requestPermission,
    getPrefs: getPrefs,
    savePrefs: savePrefs,
    startNotifications: startNotifications,
    stopNotifications: stopNotifications,
    initOnLogin: initOnLogin,
    maybePromptAfterLogin: maybePromptAfterLogin,
    registerSW: registerSW
  };
})();
