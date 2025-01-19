document.addEventListener("DOMContentLoaded", function() {
    const categoriesContainer = document.querySelector('.categories');
    const addCategoryButton = document.getElementById('add-category');
    let categories = [
        { id: 1, name: 'Food' },
        { id: 2, name: 'Rent' },
        { id: 3, name: 'Utilities' }
    ];

    function renderCategories() {
        categoriesContainer.innerHTML = ''; // Clear categories container
        categories.forEach(category => {
            const categoryElement = document.createElement('div');
            categoryElement.classList.add('category');
            categoryElement.innerHTML = `
                <button class="delete-category" data-id="${category.id}">X</button>
                <input type="text" value="${category.name}" data-id="${category.id}" />
            `;
            categoriesContainer.appendChild(categoryElement);
        });
    }

    // Add new category
    addCategoryButton.addEventListener('click', function() {
        if (categories.length < 6) {
            const newCategory = {
                id: Date.now(),
                name: 'New Category'
            };
            categories.push(newCategory);
            renderCategories();
        } else {
            alert("You can only add up to 6 categories.");
        }
    });

    // Edit category name
    categoriesContainer.addEventListener('input', function(event) {
        if (event.target.tagName === 'INPUT') {
            const categoryId = event.target.dataset.id;
            const categoryName = event.target.value;
            const category = categories.find(c => c.id == categoryId);
            category.name = categoryName;
        }
    });

    // Delete category
    categoriesContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-category')) {
            const categoryId = event.target.dataset.id;
            if (categories.length > 3) {
                categories = categories.filter(c => c.id != categoryId);
                renderCategories();
            } else {
                alert("You must always have at least 3 categories.");
            }
        }
    });

    // Initial rendering of categories
    renderCategories();

    // Logout functionality
    const logoutButton = document.getElementById('logout');
    logoutButton.addEventListener('click', function() {
        // Handle user logout (e.g., clear session, redirect to login page)
        alert("Logged out");
    });
});
