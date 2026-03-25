(function () {
  "use strict";

  var stats = [
    { label: "Sessions", value: "24", delta: "+2" },
    { label: "Total Time", value: "12.5h", delta: "+1.2h" },
    { label: "Mood Trend", value: "+15%", delta: "Up" },
    { label: "Environments", value: "8", delta: "New" }
  ];

  var moods = [
    { id: "awful",  label: "Awful", short: "😔" },
    { id: "down",   label: "Down",  short: "😕" },
    { id: "okay",   label: "Okay",  short: "😐" },
    { id: "good",   label: "Good",  short: "🙂" },
    { id: "great",  label: "Great", short: "😊" }
  ];

  var weeklyMood = [
    { day: "M", value: 40 },
    { day: "T", value: 60 },
    { day: "W", value: 45 },
    { day: "T", value: 75 },
    { day: "F", value: 55 },
    { day: "S", value: 90 },
    { day: "S", value: 85 }
  ];

  var environments = [
    { title: "Midnight Forest", meta: "Focus and Calm • 15 min", tone: "linear-gradient(135deg, #0f9f75, #198565)", mark: "F" },
    { title: "Azure Shoreline", meta: "Relaxation • 10 min", tone: "linear-gradient(135deg, #5ca7d8, #2e8b88)", mark: "O" },
    { title: "Peak Clarity", meta: "Deep Breathing • 20 min", tone: "linear-gradient(135deg, #1ea477, #185844)", mark: "P" }
  ];

  var activity = [
    { title: "Midnight Forest", time: "Yesterday • 15:30", mood: "Okay to Good", icon: "M" },
    { title: "Azure Shoreline", time: "2 days ago • 09:15", mood: "Down to Okay", icon: "A" }
  ];

  var selectedMood = "great";

  function getGreeting() {
    var hour = new Date().getHours();
    if (hour < 12) return "Good morning";
    if (hour < 17) return "Good afternoon";
    return "Good evening";
  }

  function renderStats() {
    var target = document.querySelector("[data-dashboard-stats]");
    if (!target) return;

    target.innerHTML = stats.map(function (item, index) {
      return [
        "<article class=\"card dashboard-stat\" style=\"animation-delay:" + (index * 80) + "ms\">",
        "  <p class=\"dashboard-stat__label\">" + item.label + "</p>",
        "  <div class=\"dashboard-stat__value\">",
        "    <strong>" + item.value + "</strong>",
        "    <span class=\"dashboard-stat__delta\">" + item.delta + "</span>",
        "  </div>",
        "</article>"
      ].join("");
    }).join("");
  }

  function renderMoodSelector() {
    var target = document.querySelector("[data-dashboard-mood]");
    if (!target) return;

    target.innerHTML = moods.map(function (item) {
      var selected = item.id === selectedMood;
      return [
        "<button class=\"dashboard-mood-btn" + (selected ? " is-selected" : "") + "\" type=\"button\" data-dashboard-mood-option=\"" + item.id + "\" aria-pressed=\"" + String(selected) + "\">",
        "  <span class=\"dashboard-mood-btn__face\">" + item.short + "</span>",
        "  <span class=\"dashboard-mood-btn__label\">" + item.label + "</span>",
        "</button>"
      ].join("");
    }).join("");
  }

  function renderChart() {
    var chartTarget = document.querySelector("[data-dashboard-chart]");
    var labelTarget = document.querySelector("[data-dashboard-chart-labels]");
    if (!chartTarget || !labelTarget) return;

    var moodIndex = moods.findIndex(function (item) {
      return item.id === selectedMood;
    });
    var dimThreshold = Math.max(0, 3 - moodIndex);

    chartTarget.innerHTML = weeklyMood.map(function (item, index) {
      var dim = index < dimThreshold;
      return [
        "<div class=\"dashboard-chart__bar-wrap\">",
        "  <div class=\"dashboard-chart__bar" + (dim ? " is-dim" : "") + "\" style=\"height: " + item.value + "%\" title=\"" + item.day + ": " + item.value + "\"></div>",
        "</div>"
      ].join("");
    }).join("");

    labelTarget.innerHTML = weeklyMood.map(function (item) {
      return "<span>" + item.day + "</span>";
    }).join("");
  }

  function renderEnvironments() {
    var target = document.querySelector("[data-dashboard-environments]");
    if (!target) return;

    target.innerHTML = environments.map(function (item) {
      return [
        "<article class=\"card dashboard-environment\">",
        "  <div class=\"dashboard-environment__media\" style=\"background:" + item.tone + "\">" + item.mark + "</div>",
        "  <div class=\"dashboard-environment__body\">",
        "    <h3>" + item.title + "</h3>",
        "    <p class=\"dashboard-environment__meta\">" + item.meta + "</p>",
        "  </div>",
        "</article>"
      ].join("");
    }).join("");
  }

  function renderActivity() {
    var target = document.querySelector("[data-dashboard-activity]");
    if (!target) return;

    target.innerHTML = activity.map(function (item) {
      return [
        "<article class=\"card dashboard-activity\">",
        "  <div class=\"dashboard-activity__icon\">" + item.icon + "</div>",
        "  <div class=\"dashboard-activity__body\">",
        "    <h3>" + item.title + "</h3>",
        "    <p class=\"dashboard-activity__time\">" + item.time + "</p>",
        "  </div>",
        "  <div class=\"dashboard-activity__mood\">" + item.mood + "</div>",
        "</article>"
      ].join("");
    }).join("");
  }

  function renderAll() {
    renderStats();
    renderMoodSelector();
    renderChart();
    renderEnvironments();
    renderActivity();
  }

  document.addEventListener("DOMContentLoaded", function () {
    // Time-of-day greeting
    var greetingEl = document.querySelector("[data-dashboard-greeting]");
    if (greetingEl) greetingEl.textContent = getGreeting() + ", Alex";

    renderAll();

    document.addEventListener("click", function (event) {
      var moodButton = event.target.closest("[data-dashboard-mood-option]");
      if (!moodButton) return;

      selectedMood = moodButton.getAttribute("data-dashboard-mood-option");
      renderMoodSelector();
      renderChart();
    });
  });
})();
