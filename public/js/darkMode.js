// Function to set a given theme/color-scheme
function setTheme(themeName) {
    localStorage.setItem("theme", themeName);
    document.documentElement.className = themeName;
    document.getElementById("currentMode").innerHTML =
        themeName.charAt(0).toUpperCase() + themeName.slice(1);
}

// Function to toggle between light and dark
function toggleTheme() {
    if (localStorage.getItem("theme") === "dark") {
        setTheme("light");
    } else {
        setTheme("dark");
    }
}

// Immediately invoked function to set the theme on initial load
(function () {
    if (localStorage.getItem("theme") === "dark") {
        setTheme("dark");
        document.getElementById("theme-toggle").checked = true;
    } else if (localStorage.getItem("theme") === "light") {
        setTheme("light");
        document.getElementById("theme-toggle").checked = false;
    } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
        setTheme("dark");
        document.getElementById("theme-toggle").checked = true;
    } else {
        setTheme("light");
        document.getElementById("theme-toggle").checked = false;
    }
})();
