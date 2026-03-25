(function () {
  "use strict";

  var state = {
    stepIndex: 0,
    mood: "okay",
    concerns: ["anxiety", "stress"],
    preference: "nature"
  };

  var moods = [
    { id: "awful",  label: "Awful", short: "😔" },
    { id: "down",   label: "Down",  short: "😕" },
    { id: "okay",   label: "Okay",  short: "😐" },
    { id: "good",   label: "Good",  short: "🙂" },
    { id: "great",  label: "Great", short: "😊" }
  ];

  var concerns = [
    { id: "anxiety", label: "Anxiety" },
    { id: "depression", label: "Depression" },
    { id: "stress", label: "Stress" },
    { id: "trauma", label: "Trauma" },
    { id: "sleep", label: "Sleep issues" },
    { id: "isolation", label: "Isolation" }
  ];

  var preferences = [
    {
      id: "nature",
      title: "Nature and Outdoors",
      description: "Calming forests and ocean waves",
      icon: "N",
      iconClass: "onboarding-radio__icon onboarding-radio__icon--nature"
    },
    {
      id: "abstract",
      title: "Abstract Color Therapy",
      description: "Ethereal lights and flow patterns",
      icon: "A",
      iconClass: "onboarding-radio__icon onboarding-radio__icon--abstract"
    },
    {
      id: "social",
      title: "Guided Social VR",
      description: "Group meditation in virtual spaces",
      icon: "S",
      iconClass: "onboarding-radio__icon onboarding-radio__icon--social"
    }
  ];

  var steps = [
    {
      eyebrow: "Step 1",
      title: "Welcome to your wellness assessment",
      subtitle: "A few quick prompts will help us shape your first Neuro Haven experience.",
      render: renderIntro
    },
    {
      eyebrow: "Step 2",
      title: "How are you feeling today?",
      subtitle: "Choose the option that best reflects your current state.",
      render: renderMood
    },
    {
      eyebrow: "Step 3",
      title: "Areas of concern",
      subtitle: "Select all that apply to your current experience.",
      render: renderConcerns
    },
    {
      eyebrow: "Step 4",
      title: "Therapeutic preference",
      subtitle: "Choose the virtual environment style you want us to prioritize.",
      render: renderPreferences
    }
  ];

  function $(selector) {
    return document.querySelector(selector);
  }

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function renderIntro() {
    var selectedPreference = preferences.find(function (item) {
      return item.id === state.preference;
    });

    return [
      "<div class=\"onboarding-summary\">",
      "  <div class=\"onboarding-summary__row\">",
      "    <strong>Private and paced</strong>",
      "    <span>Your responses stay on-device for now while we prepare backend integration.</span>",
      "  </div>",
      "  <div class=\"onboarding-summary__row\">",
      "    <strong>Personalized from the first session</strong>",
      "    <span>We will tailor your starting environment around your mood, concerns, and preferred therapy style.</span>",
      "  </div>",
      "  <div class=\"onboarding-summary__row\">",
      "    <strong>Current default</strong>",
      "    <span>" + escapeHtml(selectedPreference.title) + " is ready as your first suggested path.</span>",
      "  </div>",
      "</div>"
    ].join("");
  }

  function renderMood() {
    return [
      "<div class=\"onboarding-moods\" role=\"listbox\" aria-label=\"Mood selector\">",
      moods.map(function (mood) {
        var selected = state.mood === mood.id;
        return [
          "<button class=\"onboarding-mood" + (selected ? " is-selected" : "") + "\" type=\"button\" data-mood=\"" + mood.id + "\" aria-pressed=\"" + String(selected) + "\">",
          "  <span class=\"onboarding-mood__face\">" + escapeHtml(mood.short) + "</span>",
          "  <span class=\"onboarding-mood__label\">" + escapeHtml(mood.label) + "</span>",
          "</button>"
        ].join("");
      }).join(""),
      "</div>"
    ].join("");
  }

  function renderConcerns() {
    return [
      "<div class=\"onboarding-choice-grid\">",
      concerns.map(function (concern) {
        var checked = state.concerns.indexOf(concern.id) !== -1;
        return [
          "<label class=\"onboarding-check" + (checked ? " is-selected" : "") + "\" data-concern=\"" + concern.id + "\">",
          "  <input type=\"checkbox\"" + (checked ? " checked" : "") + ">",
          "  <span>" + escapeHtml(concern.label) + "</span>",
          "</label>"
        ].join("");
      }).join(""),
      "</div>"
    ].join("");
  }

  function renderPreferences() {
    return [
      "<div class=\"onboarding-radio-list\">",
      preferences.map(function (item) {
        var selected = state.preference === item.id;
        return [
          "<button class=\"onboarding-radio" + (selected ? " is-selected" : "") + "\" type=\"button\" data-preference=\"" + item.id + "\" aria-pressed=\"" + String(selected) + "\">",
          "  <span class=\"onboarding-radio__main\">",
          "    <span class=\"" + item.iconClass + "\">" + escapeHtml(item.icon) + "</span>",
          "    <span class=\"onboarding-radio__copy\">",
          "      <strong>" + escapeHtml(item.title) + "</strong>",
          "      <span>" + escapeHtml(item.description) + "</span>",
          "    </span>",
          "  </span>",
          "  <span class=\"onboarding-radio__dot\" aria-hidden=\"true\"></span>",
          "</button>"
        ].join("");
      }).join(""),
      "</div>",
      renderReviewSummary()
    ].join("");
  }

  function renderReviewSummary() {
    var mood = moods.find(function (item) {
      return item.id === state.mood;
    });
    var preference = preferences.find(function (item) {
      return item.id === state.preference;
    });
    var concernLabels = concerns
      .filter(function (item) {
        return state.concerns.indexOf(item.id) !== -1;
      })
      .map(function (item) {
        return item.label;
      });

    return [
      "<div class=\"onboarding-summary\">",
      "  <div class=\"onboarding-summary__row\">",
      "    <strong>Mood</strong>",
      "    <span>" + escapeHtml(mood ? mood.label : "Not selected") + "</span>",
      "  </div>",
      "  <div class=\"onboarding-summary__row\">",
      "    <strong>Concerns</strong>",
      "    <span>" + escapeHtml(concernLabels.join(", ") || "None selected") + "</span>",
      "  </div>",
      "  <div class=\"onboarding-summary__row\">",
      "    <strong>Preferred environment</strong>",
      "    <span>" + escapeHtml(preference ? preference.title : "Not selected") + "</span>",
      "  </div>",
      "</div>"
    ].join("");
  }

  function validateStep() {
    var errorEl = $("[data-onboarding-error]");
    errorEl.textContent = "";

    if (state.stepIndex === 2 && state.concerns.length === 0) {
      errorEl.textContent = "Select at least one concern before continuing.";
      return false;
    }

    return true;
  }

  function updateProgress() {
    var total = steps.length;
    var current = state.stepIndex + 1;
    $("[data-onboarding-step-label]").textContent = "Step " + current + " of " + total;
    $("[data-onboarding-progress]").style.width = String((current / total) * 100) + "%";
  }

  function updateActions() {
    var prevButton = $("[data-onboarding-prev]");
    var nextButton = $("[data-onboarding-next]");
    var backButton = $("[data-onboarding-back]");

    prevButton.disabled = state.stepIndex === 0;
    backButton.disabled = state.stepIndex === 0;
    prevButton.textContent = state.stepIndex === 0 ? "Back" : "Back";
    nextButton.textContent = state.stepIndex === steps.length - 1 ? "Finish" : "Continue";
  }

  function renderStep() {
    var step = steps[state.stepIndex];
    $("[data-onboarding-eyebrow]").textContent = step.eyebrow;
    $("[data-onboarding-title]").textContent = step.title;
    $("[data-onboarding-subtitle]").textContent = step.subtitle;
    $("[data-onboarding-content]").innerHTML = step.render();
    $("[data-onboarding-error]").textContent = "";
    updateProgress();
    updateActions();
  }

  function goNext() {
    if (!validateStep()) return;

    if (state.stepIndex < steps.length - 1) {
      state.stepIndex += 1;
      renderStep();
      return;
    }

    // Mark onboarding complete so returning users skip it
    localStorage.setItem('nh_onboarded', '1');
    window.location.href = "dashboard.html";
  }

  function goPrev() {
    if (state.stepIndex === 0) {
      window.location.href = "register.html";
      return;
    }

    state.stepIndex -= 1;
    renderStep();
  }

  function toggleConcern(id) {
    var index = state.concerns.indexOf(id);
    if (index === -1) {
      state.concerns.push(id);
    } else {
      state.concerns.splice(index, 1);
    }
    renderStep();
  }

  document.addEventListener("DOMContentLoaded", function () {
    // If already onboarded, skip straight to dashboard
    if (localStorage.getItem('nh_onboarded')) {
      window.location.href = 'dashboard.html';
      return;
    }

    renderStep();

    document.addEventListener("click", function (event) {
      var moodButton = event.target.closest("[data-mood]");
      var concernOption = event.target.closest("[data-concern]");
      var preferenceButton = event.target.closest("[data-preference]");
      var nextButton = event.target.closest("[data-onboarding-next]");
      var prevButton = event.target.closest("[data-onboarding-prev]");
      var backButton = event.target.closest("[data-onboarding-back]");

      if (moodButton) {
        state.mood = moodButton.getAttribute("data-mood");
        renderStep();
      }

      if (concernOption) {
        event.preventDefault();
        toggleConcern(concernOption.getAttribute("data-concern"));
      }

      if (preferenceButton) {
        state.preference = preferenceButton.getAttribute("data-preference");
        renderStep();
      }

      if (nextButton) {
        goNext();
      }

      if (prevButton || backButton) {
        goPrev();
      }
    });
  });
})();
