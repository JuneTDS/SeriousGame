$(document).ready(function() {
    var rowsPerPage = 25; // Default number of rows to display per page
    var currentPage = 1; // Current page number
    var $tableRows = $(".table tbody tr");
    var totalRows = $tableRows.length;
    var totalPages = Math.ceil(totalRows / rowsPerPage);
    var maxPageLinks = 9; // Maximum number of page links to display

    // Call the function to generate initial pagination links
    generatePaginationLinks(totalPages);

    // Show the first page on document load
    showPage(1);

    // Function to display the selected page
    function showPage(pageNumber) {
        currentPage = pageNumber; // Update the current page
        // Calculate the range of rows to show based on the pageNumber
        var startIndex = (currentPage - 1) * rowsPerPage;
        var endIndex = startIndex + rowsPerPage;
        $tableRows.hide().slice(startIndex, endIndex).show();

        // Remove the 'active' class from all page links
        $(".pagination .page-item .page-link").removeClass("active");

        // Add the 'active' class to the current page link
        $(".pagination .page-item a[data-page='" + pageNumber + "']").addClass("active");
    }

    // Function to generate pagination link
    function generatePaginationLinks(totalPages) {
        var paginationHtml = "";
        var startPage, endPage;

        // Calculate the start and end page numbers based on the selected page
        if (currentPage <= Math.floor(maxPageLinks / 2) + 1) {
            startPage = 1;
            endPage = Math.min(totalPages, maxPageLinks);
        } else if (currentPage >= totalPages - Math.floor(maxPageLinks / 2)) {
            startPage = totalPages - maxPageLinks + 1;
            endPage = totalPages;
        } else {
            startPage = currentPage - Math.floor(maxPageLinks / 2);
            endPage = currentPage + Math.floor(maxPageLinks / 2);
        }

        // Add "Previous Page" link
        paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="prev"> ❮ </a></li>';

        // Add individual page links
        for (var i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                paginationHtml += '<li class="page-item"><a class="page-link active" href="#" data-page="' + i + '">' + i + '</a></li>';
            } else {
                paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
            }
        }

        // Add "Next Page" link
        paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="next"> ❯ </a></li>';

        $("#pagination").html(paginationHtml);
    }

    // Handle click event on pagination links
    $("#pagination").on("click", ".page-link", function(event) {
        event.preventDefault();
        var pageValue = $(this).data("page");
    
        if (pageValue === "prev") {
            if (currentPage > 1) {
                showPage(currentPage - 1);
            }
        } else if (pageValue === "next") {
            if (currentPage < totalPages) {
                showPage(currentPage + 1);
            }
        } else {
            var pageNumber = parseInt(pageValue);
            showPage(pageNumber);
        }
        generatePaginationLinks(totalPages);
    });
    

    // Handle change event on "Items per Page" dropdown
    $("#items-per-page").change(function() {
        rowsPerPage = parseInt($(this).val());
        currentPage = 1;
        // Recalculate the total number of pages based on the new rowsPerPage value
        totalPages = Math.ceil(totalRows / rowsPerPage);
        showPage(currentPage);  // Show the first page with the new rows per page setting
        generatePaginationLinks(totalPages);  
    });
});