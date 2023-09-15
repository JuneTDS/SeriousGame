// Function to toggle the dropdown menu
function toggleDropdownMenu() {
    console.log('Toggle dropdown menu called');
    var dropdownContent = document.getElementById("myDropdown");
    dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
}

// Close the dropdown menu if the user clicks outside of it
window.addEventListener('click', function(event) {
    var targetElement = event.target; // Clicked element
    var accountInfo = document.querySelector('.account-info');
    var dropdownContent = document.getElementById('myDropdown');

    // Check if the clicked element is the account-info or inside the dropdown-content
    if (targetElement === accountInfo || dropdownContent.contains(targetElement)) {
        // Clicked inside the account-info or dropdown-content, do nothing
        return;
    }

    // Clicked outside the account-info and dropdown-content, hide the dropdown
    dropdownContent.style.display = 'none';
});