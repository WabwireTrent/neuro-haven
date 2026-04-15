(function () {
  "use strict";

  var environments = [
    {
      id: "calm-forest",
      title: "Calm Forest",
      durationMinutes: 15,
      mood: "calm",
      concerns: ["stress"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuAQyVG0orMy8qBxBUbWpcQ3EVw1y-oeGXt0Su9Fn5XLEAnvzBIdSHRNzuB95F76xFBVF7assLoA9P1Hhj3So3xbaY5QkrMHkzPStmU8XjPiYdgXfrbXpclXVYdpbYBrCEU_ftSKR1Pt3ytjweUGzMnibw2W2qJjIsKkEttdYNHFR1FGVFWLLdWmnUWo1x5IMboP6xZfEqaUrLR26yp-6PMEgzJHwlPJ8_nJO2bNiIlYFiR1oSVfYfH7ZatGzJte0fH4C8lwHvTKCVc",
      videoUrl: "https://www.youtube.com/embed/1ZYbU82FDwE"
    },
    {
      id: "ocean-horizon",
      title: "Ocean Horizon",
      durationMinutes: 20,
      mood: "calm",
      concerns: ["anxiety"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuCI4x7hZ30VWJgzTPUiq3InPWTL0-MTB5rh97aGI9vFxofh9SGA6ScbPM6kpYId_Q0PXIwSLRqMHjpja4SQrGQXBZcr81d8ucToJkn9NLsAHrYhhqhgAlD2P9rGnmgM_WNwPYhBkmxc2fmyeHWgzh-OkIFnRp-eHS7g0yWyh6T9ouUVscKa9D-2OHOtnrtWbANTKT2OFNolYZfFK5n5-Qp2NpXdTP3Fzdibl2w93XAX_H5S15DU4lg_kQKv00CEFCUHB85lCDkgGdM",
      videoUrl: "https://www.youtube.com/embed/xXLcW8oGWh4"
    },
    {
      id: "mountain-retreat",
      title: "Mountain Retreat",
      durationMinutes: 10,
      mood: "focus",
      concerns: ["focus"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuAWaRpRn0JIgmBHmIQ7q3Jn0PwVzQ276gHs8zexW6GvY6ufkDmCqL8x33ZySoUzJIMft5vsTwFStu5W_8FOkmZOviCKXSxSxaCz61AJRs6fGOZG4nuiOyRCQNETJdvunqYJKXjpz3aeBb-kjpNcLxZGggv3f4RQb2UX40BGWgUrqBWkOv-K8SaNKK7ZZeIjy7tN8AVdOurjXBgmju3PdKVcyZ-RD2iUUpRL6JVb0e1VxNDbYusv7fH26lCaQ_XBj61XwHIGvYoJkMg",
      videoUrl: "https://www.youtube.com/embed/L1mKOq3o3so"
    },
    {
      id: "colour-therapy",
      title: "Colour Therapy",
      durationMinutes: 30,
      mood: "calm",
      concerns: ["depression"],
      type: "gradient",
      media: "linear-gradient(135deg, #ff7a9a, #8d67ea, #5b89f0)",
      videoUrl: "https://www.youtube.com/embed/6r_OkTj9PIE"
    },
    {
      id: "desert-sunrise",
      title: "Desert Sunrise",
      durationMinutes: 15,
      mood: "energy",
      concerns: ["energy"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuAzFQ7LlOH30Lp64vKEHoYC2mHxND0k9_qbn-zR8HRLG4DFnEFG26hTMCCQIrdJTPB_q9eCTETJ0-HMJDPfbAT99OIVPJfr5_8YK16cYM56uOplDg34QEIWpJ01gohFPqxrO_g75aPZjXmA4pyUH1zlGvy1ZAcAC4R2iwbiebykUw5l818bSrH6ivNcf22j77SfFG3EK9-gADyyuKw6G8a4Ch2243qE4XdT1MPpkcaeSE_gHC7uXlUi62HyPsNcmtV00QCi9Wl8WWo",
      videoUrl: "https://www.youtube.com/embed/Io_pnSHB_k0"
    },
    {
      id: "rainy-afternoon",
      title: "Rainy Afternoon",
      durationMinutes: 45,
      mood: "calm",
      concerns: ["sleep"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuCkfc3Z64TT_upioOo3EuJtXBEdwmNy1Lh0D5yRKPCzXSPPuFY3J-rtQ2MHgMNzbw-vvKM2Rq_tZ4-LppqlcaBgC17Bg8nPU2TJYgUv6sHwf4at6NS6knfYVVgtzKMsGF_NqbEGhZB648nW7TuW5l6FPiolR20UJCFM3X9U34y02LdCofEqfMliaK8dEcyfG_wk_MzRYpPiiO8RR51EP1STYSo2pTRx0chnKhI9fEAkIESfuKpXHgfM05mNF4pblRRsA-9bVO30I1Q",
      videoUrl: "https://www.youtube.com/embed/l8QfxUDKWzY"
    }
  ];

  var tags = ["all", "anxiety", "depression", "stress", "trauma", "sleep"];

  var filters = {
    search: "",
    mood: "",
    duration: "",
    tag: "all"
  };

  function getDurationBucket(durationMinutes) {
    if (durationMinutes <= 10) return "short";
    if (durationMinutes <= 30) return "medium";
    return "long";
  }

  function renderTags() {
    var target = document.querySelector("[data-library-tags]");
    if (!target) return;

    target.innerHTML = tags.map(function (tag) {
      var isActive = filters.tag === tag;
      var label = tag === "all" ? "All" : tag.charAt(0).toUpperCase() + tag.slice(1);
      return "<button class=\"pill library-pill" + (isActive ? " pill-active" : "") + "\" type=\"button\" data-library-tag=\"" + tag + "\">" + label + "</button>";
    }).join("");
  }

  function filteredEnvironments() {
    return environments.filter(function (item) {
      var searchMatch = !filters.search || item.title.toLowerCase().indexOf(filters.search) !== -1;
      var moodMatch = !filters.mood || item.mood === filters.mood;
      var durationMatch = !filters.duration || getDurationBucket(item.durationMinutes) === filters.duration;
      var tagMatch = filters.tag === "all" || item.concerns.indexOf(filters.tag) !== -1;
      return searchMatch && moodMatch && durationMatch && tagMatch;
    });
  }

  function renderCardMedia(item) {
    if (item.type === "gradient") {
      return "<div style=\"background:" + item.media + "\"></div>";
    }

    return "<img src=\"" + item.media + "\" alt=\"" + item.title + "\">";
  }

  function renderGrid() {
    var target = document.querySelector("[data-library-grid]");
    var count = document.querySelector("[data-library-count]");
    var empty = document.querySelector("[data-library-empty]");
    if (!target || !count || !empty) return;

    var results = filteredEnvironments();
    count.textContent = results.length + " calming spaces available";
    empty.hidden = results.length !== 0;

    target.innerHTML = results.map(function (item) {
      var tagMarkup = item.concerns.map(function (tag) {
        return "<span class=\"pill\">" + tag.charAt(0).toUpperCase() + tag.slice(1) + "</span>";
      }).join("");

      return [
        "<article class=\"card library-card\">",
        "  <a href=\"#\" class=\"library-card__link\" data-video-id=\"" + item.id + "\">",
        "    <div class=\"library-card__media\">",
        renderCardMedia(item),
        "      <span class=\"library-card__duration\">" + item.durationMinutes + " min</span>",
        "    </div>",
        "    <div class=\"library-card__body\">",
        "      <h3>" + item.title + "</h3>",
        "      <div class=\"library-card__tags\">" + tagMarkup + "</div>",
        "      <div class=\"library-card__actions\">",
        "        <span class=\"btn btn-primary btn-sm\">Watch Now</span>",
        "      </div>",
        "    </div>",
        "  </a>",
        "</article>"
      ].join("");
    }).join("");

    // Add click handlers to video links
    document.addEventListener("click", function (event) {
      var link = event.target.closest("[data-video-id]");
      if (!link) return;
      event.preventDefault();
      
      var videoId = link.getAttribute("data-video-id");
      var env = environments.find(function (e) { return e.id === videoId; });
      if (env) {
        showVideoModal(env);
      }
    });
  }

  function syncInputs() {
    var search = document.querySelector("[data-library-search]");
    var mood = document.querySelector("[data-library-mood]");
    var duration = document.querySelector("[data-library-duration]");

    if (search) search.value = filters.search;
    if (mood) mood.value = filters.mood;
    if (duration) duration.value = filters.duration;
  }

  function render() {
    syncInputs();
    renderTags();
    renderGrid();
  }

  function showVideoModal(env) {
    // Create modal backdrop
    var backdrop = document.createElement("div");
    backdrop.style.cssText = "position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 999;";

    var modalContent = document.createElement("div");
    modalContent.style.cssText = "position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); z-index: 1000; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; display: flex; flex-direction: column;";

    // Close button
    var closeBtn = document.createElement("button");
    closeBtn.style.cssText = "position: absolute; top: 12px; right: 12px; background: none; border: none; font-size: 28px; cursor: pointer; color: #666; z-index: 1001; padding: 0; width: 32px; height: 32px;";
    closeBtn.innerHTML = "&times;";
    closeBtn.setAttribute("aria-label", "Close");

    // Video container
    var videoContainer = document.createElement("div");
    videoContainer.style.cssText = "width: 100%; padding-bottom: 56.25%; position: relative; background: #000;";

    // Create iframe properly
    var iframe = document.createElement("iframe");
    iframe.src = env.videoUrl + "?autoplay=1";
    iframe.setAttribute("frameborder", "0");
    iframe.setAttribute("allow", "autoplay; encrypted-media");
    iframe.setAttribute("allowfullscreen", "true");
    iframe.style.cssText = "position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;";
    videoContainer.appendChild(iframe);

    // Content section
    var contentDiv = document.createElement("div");
    contentDiv.style.cssText = "padding: 24px;";

    var title = document.createElement("h2");
    title.textContent = env.title;
    title.style.cssText = "margin: 0 0 8px 0; font-size: 24px; color: #333;";

    var duration = document.createElement("p");
    duration.textContent = "Duration: " + env.durationMinutes + " minutes";
    duration.style.cssText = "margin: 0 0 16px 0; color: #666; font-size: 14px;";

    var description = document.createElement("p");
    description.textContent = "Experience a calming " + env.title.toLowerCase() + " environment designed to help you relax and find peace.";
    description.style.cssText = "margin: 0 0 16px 0; color: #666;";

    var startBtn = document.createElement("button");
    startBtn.className = "btn btn-primary";
    startBtn.textContent = "Start Session";
    startBtn.setAttribute("data-start-session", "true");
    startBtn.style.cssText = "margin-top: 12px;";

    contentDiv.appendChild(title);
    contentDiv.appendChild(duration);
    contentDiv.appendChild(description);
    contentDiv.appendChild(startBtn);

    modalContent.appendChild(closeBtn);
    modalContent.appendChild(videoContainer);
    modalContent.appendChild(contentDiv);

    document.body.appendChild(backdrop);
    document.body.appendChild(modalContent);

    // Event handlers
    closeBtn.addEventListener("click", function () {
      backdrop.remove();
      modalContent.remove();
    });

    startBtn.addEventListener("click", function () {
      backdrop.remove();
      modalContent.remove();
      window.location.href = "/session?environment=" + env.id;
    });

    backdrop.addEventListener("click", function () {
      backdrop.remove();
      modalContent.remove();
    });

    document.addEventListener("keydown", function closeOnEscape(event) {
      if (event.key === "Escape") {
        backdrop.remove();
        modalContent.remove();
        document.removeEventListener("keydown", closeOnEscape);
      }
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    render();

    document.addEventListener("input", function (event) {
      if (event.target.matches("[data-library-search]")) {
        filters.search = event.target.value.trim().toLowerCase();
        renderGrid();
      }
    });

    document.addEventListener("change", function (event) {
      if (event.target.matches("[data-library-mood]")) {
        filters.mood = event.target.value;
        renderGrid();
      }

      if (event.target.matches("[data-library-duration]")) {
        filters.duration = event.target.value;
        renderGrid();
      }
    });

    document.addEventListener("click", function (event) {
      var tag = event.target.closest("[data-library-tag]");
      if (!tag) return;

      filters.tag = tag.getAttribute("data-library-tag");
      render();
    });
  });
})();
