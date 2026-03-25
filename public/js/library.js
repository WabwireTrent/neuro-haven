(function () {
  "use strict";

  var environments = [
    {
      title: "Calm Forest",
      durationMinutes: 15,
      mood: "calm",
      concerns: ["stress"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuAQyVG0orMy8qBxBUbWpcQ3EVw1y-oeGXt0Su9Fn5XLEAnvzBIdSHRNzuB95F76xFBVF7assLoA9P1Hhj3So3xbaY5QkrMHkzPStmU8XjPiYdgXfrbXpclXVYdpbYBrCEU_ftSKR1Pt3ytjweUGzMnibw2W2qJjIsKkEttdYNHFR1FGVFWLLdWmnUWo1x5IMboP6xZfEqaUrLR26yp-6PMEgzJHwlPJ8_nJO2bNiIlYFiR1oSVfYfH7ZatGzJte0fH4C8lwHvTKCVc"
    },
    {
      title: "Ocean Horizon",
      durationMinutes: 20,
      mood: "calm",
      concerns: ["anxiety"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuCI4x7hZ30VWJgzTPUiq3InPWTL0-MTB5rh97aGI9vFxofh9SGA6ScbPM6kpYId_Q0PXIwSLRqMHjpja4SQrGQXBZcr81d8ucToJkn9NLsAHrYhhqhgAlD2P9rGnmgM_WNwPYhBkmxc2fmyeHWgzh-OkIFnRp-eHS7g0yWyh6T9ouUVscKa9D-2OHOtnrtWbANTKT2OFNolYZfFK5n5-Qp2NpXdTP3Fzdibl2w93XAX_H5S15DU4lg_kQKv00CEFCUHB85lCDkgGdM"
    },
    {
      title: "Mountain Retreat",
      durationMinutes: 10,
      mood: "focus",
      concerns: ["focus"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuAWaRpRn0JIgmBHmIQ7q3Jn0PwVzQ276gHs8zexW6GvY6ufkDmCqL8x33ZySoUzJIMft5vsTwFStu5W_8FOkmZOviCKXSxSxaCz61AJRs6fGOZG4nuiOyRCQNETJdvunqYJKXjpz3aeBb-kjpNcLxZGggv3f4RQb2UX40BGWgUrqBWkOv-K8SaNKK7ZZeIjy7tN8AVdOurjXBgmju3PdKVcyZ-RD2iUUpRL6JVb0e1VxNDbYusv7fH26lCaQ_XBj61XwHIGvYoJkMg"
    },
    {
      title: "Colour Therapy",
      durationMinutes: 30,
      mood: "calm",
      concerns: ["depression"],
      type: "gradient",
      media: "linear-gradient(135deg, #ff7a9a, #8d67ea, #5b89f0)"
    },
    {
      title: "Desert Sunrise",
      durationMinutes: 15,
      mood: "energy",
      concerns: ["energy"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuAzFQ7LlOH30Lp64vKEHoYC2mHxND0k9_qbn-zR8HRLG4DFnEFG26hTMCCQIrdJTPB_q9eCTETJ0-HMJDPfbAT99OIVPJfr5_8YK16cYM56uOplDg34QEIWpJ01gohFPqxrO_g75aPZjXmA4pyUH1zlGvy1ZAcAC4R2iwbiebykUw5l818bSrH6ivNcf22j77SfFG3EK9-gADyyuKw6G8a4Ch2243qE4XdT1MPpkcaeSE_gHC7uXlUi62HyPsNcmtV00QCi9Wl8WWo"
    },
    {
      title: "Rainy Afternoon",
      durationMinutes: 45,
      mood: "calm",
      concerns: ["sleep"],
      type: "image",
      media: "https://lh3.googleusercontent.com/aida-public/AB6AXuCkfc3Z64TT_upioOo3EuJtXBEdwmNy1Lh0D5yRKPCzXSPPuFY3J-rtQ2MHgMNzbw-vvKM2Rq_tZ4-LppqlcaBgC17Bg8nPU2TJYgUv6sHwf4at6NS6knfYVVgtzKMsGF_NqbEGhZB648nW7TuW5l6FPiolR20UJCFM3X9U34y02LdCofEqfMliaK8dEcyfG_wk_MzRYpPiiO8RR51EP1STYSo2pTRx0chnKhI9fEAkIESfuKpXHgfM05mNF4pblRRsA-9bVO30I1Q"
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
        "  <div class=\"library-card__media\">",
        renderCardMedia(item),
        "    <span class=\"library-card__duration\">" + item.durationMinutes + " min</span>",
        "  </div>",
        "  <div class=\"library-card__body\">",
        "    <h3>" + item.title + "</h3>",
        "    <div class=\"library-card__tags\">" + tagMarkup + "</div>",
        "    <div class=\"library-card__actions\">",
        "      <a class=\"btn btn-primary btn-sm\" href=\"session.html\">Start</a>",
        "    </div>",
        "  </div>",
        "</article>"
      ].join("");
    }).join("");
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
