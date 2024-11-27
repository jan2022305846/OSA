document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.getElementById('main-content');
    const navLinks = document.querySelectorAll('.nav-link');
    const complaintForm = document.querySelector('.submit-complaint-form form'); // Get the complaint form

    // Function to dynamically load content
    function loadSection(section) {
        fetch(`load_section.php?section=${section}`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load section');
                return response.text();
            })
            .then(data => {
                mainContent.innerHTML = data;

                // Update active link styling
                navLinks.forEach(link => link.classList.remove('active'));
                const activeLink = document.querySelector(`[data-section="${section}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            })
            .catch(error => {
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

    // Handle complaint form submission with AJAX
    if (complaintForm) {
        complaintForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData(complaintForm); // Collect form data

            // Submit the form data using AJAX
            fetch('submit-complaint.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Parse JSON response
            .then(data => {
                if (data.status === 'success') {
                    // Show success message (optional)
                    alert(data.message);

                    // Load the dashboard section after complaint submission
                    loadSection('dashboard');
                } else {
                    // Show error message
                    alert(data.message);
                }
            })
            .catch(error => {
                alert("Error submitting complaint: " + error.message);
            });
        });
    }

    // Load default section
    loadSection('dashboard');
});
