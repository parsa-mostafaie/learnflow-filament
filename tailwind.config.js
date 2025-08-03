import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import colors from "tailwindcss/colors";
import preset from "./vendor/filament/support/tailwind.config.preset";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/masmerise/livewire-toaster/resources/views/*.blade.php",
        "./app/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", "Fluent Emoji Color", "Vazirmatn", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                purple: {
                    600: "#6B46C1",
                    800: "#553C9A",
                },
                ...colors,
            },
        },
    },

    plugins: [forms, typography],
    presets: [preset],
};
