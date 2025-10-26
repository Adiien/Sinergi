/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./views/**/*.php", "./src/**/*.php"],
  safelist: [
    "hidden",
    "blur-sm",
    "pointer-events-none",
    "opacity-0",
    "scale-95",
  ],
  theme: {
    extend: {
      fontFamily: {
        poppins: ['"Poppins"', "sans-serif"],
        helvetica: ['"Helvetica"', "sans-serif"],
        azeret: ['"Azeret Mono"', "monospace"],
      },
      colors: {
        "aqua-gray": "var(--aqua-gray)",
        blue: "var(--blue)",
        "blue-gray": "var(--blue-gray)",
        gray: "var(--gray)",
        lavender: "var(--lavender)",
        "variable-collection-aqua-gray": "var(--variable-collection-aqua-gray)",
        "variable-collection-blue-gray": "var(--variable-collection-blue-gray)",
        "variable-collection-color": "var(--variable-collection-color)",
        "variable-collection-lavender": "var(--variable-collection-lavender)",
        "variable-collection-netral-gray":
          "var(--variable-collection-netral-gray)",
        "variable-collection-white": "var(--variable-collection-white)",
        white: "var(--white)",
      },
    },
  },
  plugins: [],
};
