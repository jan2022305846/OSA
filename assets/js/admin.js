document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.getElementById('main-content');
    const navLinks = document.querySelectorAll('.nav-link');
    const filterForm = document.querySelector('form');

    // Function to dynamically load content
    function loadSection(section, queryParams = '') {
        console.log(`Loading section: ${section}, Query: ${queryParams}`); // Debug log
        fetch(`adminload.php?section=${section}${queryParams}`)
            .then(response => {
                if (!response.ok) throw new Error(`Failed to load section ${section}`);
                return response.text();
            })
            .then(data => {
                console.log(`Section loaded: ${section}`); // Debug log
                mainContent.innerHTML = data;
    
                // Update active link styling
                navLinks.forEach(link => link.classList.remove('active'));
                document.querySelector(`[data-section="${section}"]`).classList.add('active');
            })
            .catch(error => {
                console.error(`Error loading section ${section}: ${error.message}`);
                mainContent.innerHTML = `<p class="text-danger">Error: ${error.message}</p>`;
            });
    }       

    // Attach click events to navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const section = e.target.getAttribute('data-section');
            loadSection(section);
        });
    });

    // Handle filter form submission dynamically
    if (filterForm) {
        filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(filterForm);
            const queryParams = `&${new URLSearchParams(formData).toString()}`;
            loadSection('manage-complaint', queryParams);
        });
    }

    // Load default section
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section') || 'dashboard';
    loadSection(section);
});
