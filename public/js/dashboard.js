$(document).ready(function () {
    var today = new Date();
    var curHr = today.getHours();
    var greetings = $(".greetings");
    if (curHr < 12) {
        greetings.html("Good Morning, ");
    } else if (curHr < 18) {
        greetings.html("Good Afternoon, ");
    } else {
        greetings.html("Good Evening, ");
    }

    $(".icon").each(function () {
        $(this).click(function () {
            $(".icon.selected").removeClass("selected");
            $(this).addClass("selected");
            $("#selected-icon").val($(this).data("icon"));
            $(".icon").removeClass(
                "border-2 p-2 rounded-lg border-primaryDark dark:border-primaryLight"
            );
            $(this).addClass(
                "border-2 p-2 rounded-lg border-primaryDark dark:border-primaryLight"
            );
        });
    });

    $(".color").each(function () {
        $(this).click(function () {
            var colorData = $(this).data("color"); // Store color data in a variable
            $(".color.selected").removeClass("selected");
            $(this).addClass("selected");
            $("#selected-color").val("text-" + colorData);
            $(".icon i").each(function () {
                $(this).attr(
                    "class",
                    $(this)
                        .attr("class")
                        .replace(
                            /text-\w+-500/,
                            "text-" + colorData // Use the stored color data
                        )
                );
            });
        });
    });

    $("#initial_balance_display").on("input", function () {
        this.value = this.value
            .replace(/[^0-9.]/g, "")
            .replace(/(\..*)\./g, "$1");
        let value = this.value.replace(/,/g, "");
        let numericValue = parseFloat(value);
        if (isNaN(numericValue)) {
            numericValue = 0;
        }
        $("#initial_balance").val(numericValue);
        let parts = this.value.split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        this.value = parts.join(".");
    });

    $("#amount_display").on("input", function () {
        this.value = this.value
            .replace(/[^0-9.]/g, "")
            .replace(/(\..*)\./g, "$1");
        let value = this.value.replace(/,/g, "");
        let numericValue = parseFloat(value);
        if (isNaN(numericValue)) {
            numericValue = 0;
        }
        $("#amount").val(numericValue);
        let parts = this.value.split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        this.value = parts.join(".");
    });

    $("#fee_display").on("input", function () {
        this.value = this.value
            .replace(/[^0-9.]/g, "")
            .replace(/(\..*)\./g, "$1");
        let value = this.value.replace(/,/g, "");
        let numericValue = parseFloat(value);
        if (isNaN(numericValue)) {
            numericValue = 0;
        }
        $("#fee").val(numericValue);
        let parts = this.value.split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        this.value = parts.join(".");
    });

    let progressBars = $(".progress");
    let progressTexts = $(".progress-text");

    progressBars.each(function (index) {
        let percentage = parseInt(progressTexts[index].textContent);
        let radius = this.r.baseVal.value;
        let circumference = 2 * Math.PI * radius;
        let offset = circumference - (percentage / 100) * circumference;
        this.style.strokeDasharray = `${circumference} ${circumference}`;
        this.style.strokeDashoffset = circumference;
        setTimeout(() => {
            this.style.transition = "stroke-dashoffset 1s ease-in-out";
            this.style.strokeDashoffset = offset;
        }, 100);
    });

    var currentMonth = $("#current_month").val();
    var currentYear = $("#current_year").val();
    var months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ];

    $("#prev_month").click(function () {
        if (currentMonth == "Jan") {
            currentMonth = "Dec";
            currentYear = parseInt(currentYear) - 1;
        } else {
            currentMonth = months[months.indexOf(currentMonth) - 1];
        }
        window.location.href =
            "/dashboard?month=" + currentMonth + "&year=" + currentYear;
    });

    $("#next_month").click(function () {
        if (currentMonth == "Dec") {
            currentMonth = "Jan";
            currentYear = parseInt(currentYear) + 1;
        } else {
            currentMonth = months[months.indexOf(currentMonth) + 1];
        }
        window.location.href =
            "/dashboard?month=" + currentMonth + "&year=" + currentYear;
    });

    $("#current_month").change(function () {
        updateURL("");
    });
    $("#current_year").change(function () {
        updateURL("");
    });
    $("#prev_wallet").click(function () {
        updateURL("prev");
    });
    $("#next_wallet").click(function () {
        updateURL("next");
    });
    $("#transaction_wallet_filter").change(function () {
        updateURL();
    });
    $("#currencies_filter").change(function () {
        updateURL();
    });
    $(".chartOption").click(function () {
        let option = $(this).data("chart-option");
        $("#chart-option").val(option);
        updateURL("", option);
    });
    if ($("#go_to_current_month").length) {
        $("#go_to_current_month").click(function () {
            window.location.href = "/dashboard";
        });
    }

    function updateURL(defaultWallet = "", charO = "") {
        var currentMonth = $("#current_month").val();
        var currentYear = $("#current_year").val();
        var currentWalletTransactions =
            defaultWallet == "" ? $("#transaction_wallet_filter").val() : "";
        var currentCurrency = $("#currencies_filter").val();
        var chartOption = charO == "" ? $("#chart-option").val() : charO;

        window.location.href =
            "/dashboard?month=" +
            currentMonth +
            "&year=" +
            currentYear +
            "&wallet=" +
            currentWalletTransactions +
            "&default_wallet=" +
            defaultWallet +
            "&currency=" +
            currentCurrency +
            "&chart-option=" +
            chartOption;
    }
});

function selectCategory(name, icon, id) {
    $("#selectedCategory").text(name);
    $("#categoryIcon").attr(
        "class",
        "fa-solid " + icon + " text-lg text-primaryDark dark:text-primaryLight"
    );
    $("#categoryDropdown").addClass("hidden");
    $("#category").val(id);
}

$(document).on("click", function (event) {
    var $categoryDropdown = $("#categoryDropdown");
    var $selectedCategory = $("#selectedCategory");
    if (
        !$categoryDropdown.is(event.target) &&
        !$selectedCategory.is(event.target) &&
        $categoryDropdown.has(event.target).length === 0
    ) {
        $categoryDropdown.addClass("hidden");
    }
});

$("#selectedCategory").on("click", function () {
    $("#categoryDropdown").toggleClass("hidden");
});

let $transactionType = $("#transaction_type");
let $categoryInput = $("#expense_income_category_input");
let $borrowLendInput = $("#borrow_lend_input");
let $internalTransferInput = $("#internal_transfer_input");

$transactionType.on("change", function () {
    if (
        $transactionType.val() === "expense" ||
        $transactionType.val() === "income"
    ) {
        $categoryInput.removeClass("hidden");
        $borrowLendInput.addClass("hidden");
        $internalTransferInput.addClass("hidden");
    } else if (
        $transactionType.val() === "borrow" ||
        $transactionType.val() === "lend"
    ) {
        $categoryInput.addClass("hidden");
        $borrowLendInput.removeClass("hidden");
        $internalTransferInput.addClass("hidden");
    } else if ($transactionType.val() === "internal_transfer") {
        $categoryInput.addClass("hidden");
        $borrowLendInput.addClass("hidden");
        $internalTransferInput.removeClass("hidden");
    }
});