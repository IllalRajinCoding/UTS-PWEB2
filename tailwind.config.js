module.exports = {
  darkMode: "class",
  content: [
    // Menggantikan 'purge' di versi baru
    "./src/**/*.{html,js,php}",
    "./public/**/*.html",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#4361ee",
        secondary: "#3f37c9",
      },
    },
  },
  plugins: [],
};
