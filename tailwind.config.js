module.exports = {
  purge: {
    enabled: false,
    content:[
    './resources/js/components/**/*.vue',
    './resources/views/**/*.blade.php',
    './resources/views/*.blade.php',
    './resources/sass/*.scss'
    ]
  },
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
