module.exports = {
  purge: [],
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        teal: {
          50: '#e3f7f7',
          100: '#c6efef',
          200: '#a7e7e8',
          300: '#85dfe0',
          400: '#5bd7d9',
          500: '#00ced1',
          600: '#19a8ab',
          800: '#1f8486',
          900: '#1f6263',
          1000: '#1b4142',
        },
      }
    },
    fontFamily: {
      'sans': ['Quicksand', 'system-ui'],
      'serif': ['ui-serif', 'Georgia'],
      'mono': ['"Overpass Mono"', 'SFMono-Regular'],
      'snowtricks': ['kufam', 'cursive']
    }
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
