import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./node_modules/flowbite/**/*.js",
    ],

    darkMode: "class",

    theme: {
        extend: {
            fontFamily: {
                sans: ["poppins", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primaryLight: "#f5f6f8",
                primaryDark: "#1d2127",
            },
        },
    },

    plugins: [forms, require("flowbite/plugin")],
};
