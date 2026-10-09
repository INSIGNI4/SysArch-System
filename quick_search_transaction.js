document.addEventListener("DOMContentLoaded", function () {

    const searchInput =
        document.getElementById("transactionQuickSearch");

    if (!searchInput) return;

    const salesContainer =
        document.getElementById("sales-view-container");

    const transactionContainer =
        document.getElementById("transaction-view-container");

    let searchTimer = null;


    const originalSalesBody =
    salesContainer.querySelector("tbody").innerHTML;

    const originalTransactionBody =
        transactionContainer.querySelector("tbody").innerHTML;


    searchInput.addEventListener("input", function () {

        clearTimeout(searchTimer);

        const query =
            this.value.trim();

        searchTimer = setTimeout(function () {

            searchDatabase(query);

        }, 300);

    });


    async function searchDatabase(query) {

        let activeContainer = null;
        let type = "";


        /* =========================================
           DETERMINE WHICH TAB IS CURRENTLY VISIBLE
           ========================================= */

        if (
            window.getComputedStyle(salesContainer).display !== "none"
        ) {

            activeContainer = salesContainer;
            type = "sales";

        } else if (
            window.getComputedStyle(transactionContainer).display !== "none"
        ) {

            activeContainer = transactionContainer;
            type = "transaction";
        }


        if (!activeContainer) return;


        /* =========================================
           EMPTY SEARCH
           ========================================= */

        if (query === "") {

            const tbody =
                activeContainer.querySelector("tbody");

            if (type === "sales") {
                tbody.innerHTML = originalSalesBody;
            }

            if (type === "transaction") {
                tbody.innerHTML = originalTransactionBody;
            }

            return;
        }

        /* =========================================
           DATABASE SEARCH
           ========================================= */

        try {

            const response =
                await fetch(
                    "search_sales.php?type=" +
                    encodeURIComponent(type) +
                    "&q=" +
                    encodeURIComponent(query)
                );


            const result =
                await response.json();


            if (!result.success) {

                console.error(
                    result.error || "Search failed."
                );

                return;
            }


            renderResults(
                activeContainer,
                type,
                result.data
            );


        } catch (error) {

            console.error(
                "Search error:",
                error
            );

        }

    }


    function renderResults(
        container,
        type,
        data
    ) {

        const tbody =
            container.querySelector("tbody");

        if (!tbody) return;


        tbody.innerHTML = "";


        if (data.length === 0) {

            const colspan =
                type === "transaction" ? 8 : 10;

            tbody.innerHTML = `
                <tr>
                    <td
                        colspan="${colspan}"
                        style="text-align:center;"
                    >
                        No matching records found.
                    </td>
                </tr>
            `;

            return;
        }


        /* =========================================
           TRANSACTION RESULTS
           ========================================= */

        if (type === "transaction") {

            data.forEach(function (row, index) {

                const tr =
                    document.createElement("tr");

                tr.innerHTML = `
                    <td>${index + 1}</td>

                    <td>
                        SALES-${row.Sales_ID}
                    </td>

                    <td>
                        ${row.ReferenceNo ?? ""}
                    </td>

                    <td>
                        ${row.User_ID ?? ""}
                    </td>

                    <td>
                        ${row.total_amount ?? ""}
                    </td>

                    <td>
                        ${row.amount_tendered ?? ""}
                    </td>

                    <td>
                        ${row.change_amt ?? ""}
                    </td>

                    <td>
                        ${row.date_created ?? ""}
                    </td>
                `;

                tbody.appendChild(tr);

            });

            return;
        }


        /* =========================================
           SALES RESULTS
           ========================================= */

        if (type === "sales") {

            data.forEach(function (row, index) {

                const tr =
                    document.createElement("tr");

                const batch =
                    row.BatchNum
                    ? row.BatchNum
                    : "- - - N/A - - -";


                tr.innerHTML = `
                    <td>${index + 1}</td>

                    <td>
                        ${row.SalesItem_ID}
                    </td>

                    <td>
                        SALES-${row.Sales_ID}
                    </td>

                    <td>
                        ${row.Product_ID}
                    </td>

                    <td>
                        ${row.ProductName ?? "Unknown Product"}
                    </td>

                    <td style="color:red;">
                        ${batch}
                    </td>

                    <td>
                        <span
                            style="
                                color:darkgreen;
                                font-weight:bolder;
                                float:left;
                            "
                        >
                            PHP
                        </span>

                        ${Number(row.Unit_Price || 0)
                            .toFixed(2)}
                    </td>

                    <td>
                        ${row.Quantity}
                    </td>

                    <td>
                        <span
                            style="
                                color:darkgreen;
                                font-weight:bolder;
                                float:left;
                            "
                        >
                            PHP
                        </span>

                        ${Number(row.TotalPrice || 0)
                            .toFixed(2)}
                    </td>

                    <td>
                        ${row.date_created ?? ""}
                    </td>
                `;

                tbody.appendChild(tr);

            });

        }

    }

});