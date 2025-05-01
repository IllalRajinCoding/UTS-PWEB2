// src/theme.js
document.addEventListener("DOMContentLoaded", function () {
  // Versi paling sederhana yang pasti bekerja
  document.getElementById("toggle").addEventListener("click", function () {
    const html = document.documentElement;
    const isDark = html.classList.toggle("dark");
    localStorage.setItem("theme", isDark ? "dark" : "light");

    // Animasi icon
    this.querySelector("svg path").style.transform = isDark
      ? "rotate(180deg)"
      : "rotate(0deg)";
  });

  // Terapkan tema yang tersimpan saat load
  const savedTheme = localStorage.getItem("theme");
  if (
    savedTheme === "dark" ||
    (!savedTheme && window.matchMedia("(prefers-color-scheme: dark)").matches)
  ) {
    document.documentElement.classList.add("dark");
  }
  // Mobile Menu Toggle Functionality
  const mobileMenuToggle = document.querySelector(
    '[data-collapse-toggle="navbar-default"]'
  );
  const mobileMenu = document.getElementById("mobile-menu");

  // Debug: Log mobile menu elements
  console.log("Mobile menu elements:", {
    mobileMenuToggle,
    mobileMenu,
  });

  if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener("click", function () {
      mobileMenu.classList.toggle("hidden");
      console.log(
        "Mobile menu toggled. Visible:",
        !mobileMenu.classList.contains("hidden")
      );
    });
  } else {
    console.warn("Mobile menu toggle elements not found!");
  }

  // Add transition to the toggle icon if not already present
  if (toggleIcon) {
    toggleIcon.style.transition = "transform 0.3s ease";
  }
});

// Debug: Check if script loaded
console.log("theme.js loaded successfully");
