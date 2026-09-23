/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/views/front/**/*.blade.php",
      "./resources/views/vendor/pagination/tailwind.blade.php",
      './node_modules/tw-elements/dist/js/**/*.js'
    ],
    theme: {
      extend: {
        colors: {
          'primary': '#10664d',
        },

        container: {
          center: true,
          padding: '1rem'
        }
      },
    },
    plugins: [
      require('tw-elements/dist/plugin'),
      function ({ addComponents }) {
        addComponents({
          '.container': {
            maxWidth: '100%',
            '@screen sm': {
              maxWidth: '576px',
            },
            '@screen md': {
              maxWidth: '768px',
            },
            '@screen lg': {
              maxWidth: '922px',
            },
            '@screen xl': {
              maxWidth: '1240px',
            },
          }
        })
      }
    ],
  }

