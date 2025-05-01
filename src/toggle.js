const toggleBtn = document.querySelector("#toggle");
const toggleIcon = document.querySelector("#toggle-icon");

toggleBtn.addEventListener("click", function () {
  const isDark = document.documentElement.classList.toggle("dark");

  if (isDark) {
    toggleIcon.setAttribute(
      "d",
      "M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
    );
  } else {
    toggleIcon.setAttribute(
      "d",
      "M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
    );
  }

  // Save preference to localStorage
  localStorage.theme = isDark ? "dark" : "light";
});

tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        primary: "#4361ee",
        secondary: "#3f37c9",
      },
    },
  },
};
