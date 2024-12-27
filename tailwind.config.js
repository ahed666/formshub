import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js"
    ],

    theme: {
        extend: {
            variants: {
                
                  margin: ['rtl', 'ltr'],
                
            },
            keyframes: {
                slideIn: {
                  '0%': { transform: 'translateX(100%)', opacity: 0 },
                  '100%': { transform: 'translateX(0)', opacity: 1 },
                },
                slideOut: {
                    '0%': { transform: 'translateX(0)', opacity: 1 },
                    '100%': { transform: 'translateX(100%)', opacity: 0 },
                },
            },
            animation: {
                'slide-in': 'slideIn 0.5s ease-in-out',
                'slide-out': 'slideOut 0.5s ease-in-out',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            width: {
                'webkit-fill-available': '-webkit-fill-available',
              },
            screens: {
                'xs': '200px',    // Custom extra small breakpoint
                'sm': '480px',    // Small breakpoint
                'md': '768px',    // Medium breakpoint
                'lg': '1024px',   // Large breakpoint
                'xl': '1280px',   // Extra large breakpoint
                '2xl': '1536px',  // 2X large breakpoint
              },
              colors:{

                secondary_blue:'#1277D1',
                primary_red:'#dc2626',
                secondary_red:'#ef4444',
                svg_primary:'#878787',
                primary_green:'#16a34a',
                valid:'#15a34a',
                primary_blue:"#EDF5FF",
            },

        },
    },

    plugins: [forms, typography,require('flowbite/plugin')],
};
