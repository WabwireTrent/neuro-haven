(function () {
  "use strict";

  var VRDetector = {
    status: "unknown",
    headsetName: null,
    method: null,
    pollInterval: null,
    listeners: [],
    CHECK_INTERVAL: 5000,
    USB_VIDS: {
      "0x2833": "Meta Quest (Oculus)",
      "0x2e5a": "Meta Quest 2/3/Pro",
      "0x0502": "Oculus Rift",
      "0x21a3": "HTC Vive",
      "0x0bb4": "HTC",
      "0x28de": "Valve",
      "0x2b24": "Pico",
      "0x054c": "PlayStation VR (Sony)",
    },
  };

  VRDetector.addEventListener = function (fn) {
    VRDetector.listeners.push(fn);
  };

  VRDetector.removeEventListener = function (fn) {
    VRDetector.listeners = VRDetector.listeners.filter(function (f) {
      return f !== fn;
    });
  };

  VRDetector._notify = function () {
    var state = VRDetector.getState();
    VRDetector.listeners.forEach(function (fn) {
      try { fn(state); } catch (e) {}
    });
    var event = new CustomEvent("vr-detection-changed", { detail: state });
    document.dispatchEvent(event);
  };

  VRDetector.getState = function () {
    return {
      status: VRDetector.status,
      headsetName: VRDetector.headsetName,
      method: VRDetector.method,
      connected: VRDetector.status === "connected",
    };
  };

  VRDetector._setStatus = function (status, headsetName, method) {
    if (
      VRDetector.status === status &&
      VRDetector.headsetName === headsetName
    ) {
      return;
    }
    VRDetector.status = status;
    VRDetector.headsetName = headsetName || null;
    VRDetector.method = method || null;

    var el = document.getElementById("vr-headset-status");
    if (el) {
      el.className = "vr-status vr-status--" + status;
      el.innerHTML =
        '<span class="vr-status__dot"></span>' +
        '<span class="vr-status__text">' +
        VRDetector._label(status, headsetName) +
        "</span>";
    }

    document.body.setAttribute("data-vr-headset", status);
    VRDetector._notify();
  };

  VRDetector._label = function (status, headsetName) {
    switch (status) {
      case "connected":
        return headsetName
          ? headsetName + " Connected"
          : "VR Headset Connected";
      case "not-connected":
        return "No VR Headset Detected";
      case "unsupported":
        return "VR Not Supported";
      default:
        return "Checking VR Headset...";
    }
  };

  VRDetector._checkXR = function () {
    return new Promise(function (resolve) {
      var k = "unknown";
      if (!window.navigator.xr) {
        resolve({ status: "unsupported", headsetName: null, method: "xr" });
        return;
      }

      window.navigator.xr
        .isSessionSupported("immersive-vr")
        .then(function (supported) {
          if (supported) {
            resolve({ status: "connected", headsetName: "WebXR VR", method: "xr" });
          } else {
            resolve({ status: "not-connected", headsetName: null, method: "xr" });
          }
        })
        .catch(function () {
          resolve({ status: "unsupported", headsetName: null, method: "xr" });
        });
    });
  };

  VRDetector._checkUSB = function () {
    return new Promise(function (resolve) {
      if (!window.navigator.usb) {
        resolve(null);
        return;
      }

      window.navigator.usb
        .getDevices()
        .then(function (devices) {
          if (!devices || devices.length === 0) {
            resolve(null);
            return;
          }

          for (var i = 0; i < devices.length; i++) {
            var d = devices[i];
            var vid = "0x" + d.vendorId.toString(16).padStart(4, "0");
            if (VRDetector.USB_VIDS[vid]) {
              resolve({
                status: "connected",
                headsetName: VRDetector.USB_VIDS[vid],
                method: "usb",
              });
              return;
            }
          }
          resolve(null);
        })
        .catch(function (e) {
          resolve(null);
        });
    });
  };

  VRDetector._checkUserAgent = function () {
    var ua = (navigator.userAgent || "").toLowerCase();
    if (ua.indexOf("oculus") !== -1) {
      return { status: "connected", headsetName: "Meta Quest (Browser)", method: "ua" };
    }
    if (ua.indexOf("quest") !== -1) {
      return { status: "connected", headsetName: "Meta Quest (Browser)", method: "ua" };
    }
    if (ua.indexOf("vr") !== -1 && ua.indexOf("mobile") !== -1) {
      return { status: "connected", headsetName: "VR Browser", method: "ua" };
    }
    return null;
  };

  VRDetector._checkPoll = function () {
    return new Promise(function (resolve) {
      var checks = [];

      checks.push(
        VRDetector._checkXR().then(function (result) {
          if (result.status === "connected") return result;
          return null;
        })
      );

      if (navigator.usb) {
        checks.push(
          VRDetector._checkUSB().then(function (result) {
            if (result) return result;
            return null;
          })
        );
      }

      var uaResult = VRDetector._checkUserAgent();
      if (uaResult) {
        checks.push(Promise.resolve(uaResult));
      }

      Promise.all(checks).then(function (results) {
        var best = null;
        var priority = { xr: 3, usb: 2, ua: 1 };
        var bestPrio = -1;

        for (var i = 0; i < results.length; i++) {
          var r = results[i];
          if (r && r.status === "connected") {
            var p = priority[r.method] || 0;
            if (p > bestPrio) {
              bestPrio = p;
              best = r;
            }
          }
        }

        if (best) {
          resolve(best);
        } else if (
          results.some(function (r) {
            return r && r.status === "not-connected";
          })
        ) {
          resolve({ status: "not-connected", headsetName: null, method: "xr" });
        } else {
          resolve(null);
        }
      });
    });
  };

  VRDetector.detect = function () {
    return VRDetector._checkPoll().then(function (result) {
      if (result && result.status === "connected") {
        VRDetector._setStatus("connected", result.headsetName, result.method);
      } else if (
        result &&
        result.status === "not-connected"
      ) {
        VRDetector._setStatus("not-connected", null, result.method);
      } else {
        VRDetector._setStatus("unsupported", null, "none");
      }
      return VRDetector.getState();
    });
  };

  VRDetector.startPolling = function (intervalMs) {
    VRDetector.stopPolling();
    VRDetector.detect();
    VRDetector.pollInterval = setInterval(
      VRDetector.detect,
      intervalMs || VRDetector.CHECK_INTERVAL
    );
  };

  VRDetector.stopPolling = function () {
    if (VRDetector.pollInterval) {
      clearInterval(VRDetector.pollInterval);
      VRDetector.pollInterval = null;
    }
  };

  window.VRDetector = VRDetector;

  document.addEventListener("DOMContentLoaded", function () {
    VRDetector.startPolling();
  });
})();
