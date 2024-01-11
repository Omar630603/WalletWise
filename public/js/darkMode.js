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
    let themeToggle = document.getElementById("theme-toggle");
    let themeToggleSideMenu = document.getElementById("theme-toggle-side-menu");

    if (localStorage.getItem("theme") === "dark") {
        setTheme("dark");
        if (themeToggle) themeToggle.checked = true;
        if (themeToggleSideMenu) themeToggleSideMenu.checked = true;
    } else if (localStorage.getItem("theme") === "light") {
        setTheme("light");
        if (themeToggle) themeToggle.checked = false;
        if (themeToggleSideMenu) themeToggleSideMenu.checked = false;
    } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
        setTheme("dark");
        if (themeToggle) themeToggle.checked = true;
        if (themeToggleSideMenu) themeToggleSideMenu.checked = true;
    } else {
        setTheme("light");
        if (themeToggle) themeToggle.checked = false;
        if (themeToggleSideMenu) themeToggleSideMenu.checked = false;
    }
})();
