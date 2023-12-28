function setTheme(themeName) {
    localStorage.setItem("theme", themeName);
    document.documentElement.className = themeName;
}

// Function to toggle between light and dark
function toggleTheme() {
    if (localStorage.getItem("theme") === "dark") {
        setTheme("light");
    } else {
        setTheme("dark");
    }
}

(function () {
    if (localStorage.getItem("theme") === "dark") {
        setTheme("dark");
        document.getElementById("theme-toggle").checked = true;
        document.getElementById("theme-toggle-side-menu").checked = true;
    } else if (localStorage.getItem("theme") === "light") {
        setTheme("light");
        document.getElementById("theme-toggle").checked = false;
        document.getElementById("theme-toggle-side-menu").checked = false;
    } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
        setTheme("dark");
        document.getElementById("theme-toggle").checked = true;
        document.getElementById("theme-toggle-side-menu").checked = true;
    } else {
        setTheme("light");
        document.getElementById("theme-toggle").checked = false;
        document.getElementById("theme-toggle-side-menu").checked = false;
    }
})();
