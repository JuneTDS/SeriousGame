$(document).ready(function() {
    var rowsPerPage = 5; // Default number of rows to display per page
    var $tableRows = $(".table tbody tr");

    // Function to display the selected page
    function showPage(pageNumber) {
        $tableRows.hide(); // Hide all rows
        // Calculate the range of rows to show based on the pageNumber
        var startIndex = (pageNumber - 1) * rowsPerPage;
        var endIndex = startIndex + rowsPerPage;
        $tableRows.slice(startIndex, endIndex).show(); // Show the selected rows

        // Remove the 'active' class from all page links
        $(".pagination .page-item .page-link").removeClass("active");

        // Add the 'active' class to the current page link
        $(".pagination .page-item a[data-page='" + pageNumber + "']").addClass("active");
    }

    // Function to generate pagination links
    function generatePaginationLinks(totalPages) {
        var paginationHtml = "";

        // Add "First Page" link
        paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="first"> ❮ </a></li>';

        // Add individual page links
        for (var i = 1; i <= totalPages; i++) {
            // Add the 'active' class to the first page link
            if (i === 1) {
                paginationHtml += '<li class="page-item"><a class="page-link active" href="#" data-page="' + i + '">' + i + '</a></li>';
            } else {
                paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
            }
        }

        // Add "Last Page" link
        paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="last"> ❯ </a></li>';

        $("#pagination").html(paginationHtml);
    }

    // Call the function to generate initial pagination links
    generatePaginationLinks(Math.ceil($tableRows.length / rowsPerPage));

    // Show the first page on document load
    showPage(1);

    // Handle click event on pagination links
    $("#pagination").on("click", ".page-link", function(event) {
        event.preventDefault();
        var pageValue = $(this).data("page");

        if (pageValue === "first") {
            showPage(1);
        } else if (pageValue === "last") {
            var totalPages = Math.ceil($tableRows.length / rowsPerPage);
            showPage(totalPages);
        } else {
            var pageNumber = parseInt(pageValue);
            showPage(pageNumber);
        }
    });

    // Handle change event on "Items per Page" dropdown
    $("#items-per-page").change(function() {
        rowsPerPage = parseInt($(this).val());
        showPage(1); // Show the first page with the new rows per page setting
        generatePaginationLinks(Math.ceil($tableRows.length / rowsPerPage));
    });
});