import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['"Fraunces"', 'Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Brand: deep navy/indigo for travel — authoritative + premium
                brand: {
                    50:  '#eef4fb',
                    100: '#d6e4f3',
                    200: '#aec8e7',
                    300: '#7da7d6',
                    400: '#4d83c0',
                    500: '#2966a8',
                    600: '#1d4f8f',
                    700: '#0f4c81', // primary anchor (preserved from existing palette)
                    800: '#0c3a64',
                    900: '#082848',
                    950: '#04162b',
                },
                accent: {
                    50:  '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f97316',
                    600: '#ea580c',
                    700: '#c2410c',
                },
                ink: {
                    50:  '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                },
            },
            boxShadow: {
                card: '0 1px 2px 0 rgb(15 23 42 / 0.04), 0 1px 3px 0 rgb(15 23 42 / 0.08)',
                'card-hover': '0 4px 6px -1px rgb(15 23 42 / 0.08), 0 2px 4px -2px rgb(15 23 42 / 0.06)',
                'brand-glow': '0 10px 25px -5px rgb(15 76 129 / 0.35)',
            },
            backgroundImage: {
                'hero-gradient': 'linear-gradient(135deg, #0f4c81 0%, #0c3a64 50%, #1d4f8f 100%)',
                'hero-overlay': 'linear-gradient(180deg, rgba(8,40,72,0.55) 0%, rgba(8,40,72,0.25) 100%)',
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-out',
                'slide-up': 'slideUp 0.4s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(8px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },

    plugins: [forms],
};
