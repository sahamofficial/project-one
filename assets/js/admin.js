// Delete confirmation for categories
// This script uses SweetAlert2 for confirmation dialogs
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const redirectURL = `manage-categories.php?del=${id}`;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = redirectURL;
                }
            });
        });
    });
});


// Delete confirmation for products
// This script uses SweetAlert2 for confirmation dialogs
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-product-btn');

    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const redirectURL = `manage-products.php?del=${id}`;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = redirectURL;
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.dropdown');
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');
  
    dropdownToggle.addEventListener('click', function(e) {
      e.preventDefault();
      dropdown.classList.toggle('active');
    });
  
    document.addEventListener('click', function(e) {
      if (!dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
      }
    });
  
    dropdownMenu.querySelectorAll('.dropdown-item').forEach(item => {
      item.addEventListener('click', function() {
        dropdown.classList.remove('active');
      });
    });
  
    dropdown.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        dropdown.classList.toggle('active');
      } else if (e.key === 'Escape') {
        dropdown.classList.remove('active');
      }
    });
  });