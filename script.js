document.addEventListener('DOMContentLoaded', function () {
    function attachCardListeners() {
        const cards = document.querySelectorAll('.student-card');

        cards.forEach(card => {
            card.addEventListener('click', () => {
                // Fill the form inputs from data attributes
                const studentId = card.dataset.studentId;
                document.getElementById('student-id').value = studentId;
                document.getElementById('student-id-hidden').value = studentId;

                document.getElementById('last-name').value = card.dataset.lname;
                document.getElementById('first-name').value = card.dataset.fname;
                document.getElementById('sex').value = card.dataset.sex.toLowerCase();
                document.getElementById('course').value = card.dataset.course;

                // Uncheck all modality checkboxes
                document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

                // Check selected modalities
                const modalities = card.dataset.modality.split(',');
                modalities.forEach(mod => {
                    const trimmed = mod.trim().toLowerCase();
                    const checkbox = document.querySelector(`input[type="checkbox"][value="${trimmed}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            });
        });
    }

    attachCardListeners();

    // Clear form button behavior
    document.getElementById('clear-btn').addEventListener('click', () => {
        document.getElementById('student-id').value = "";
        document.getElementById('student-id-hidden').value = "";
        document.getElementById('last-name').value = "";
        document.getElementById('first-name').value = "";
        document.getElementById('sex').value = "";
        document.getElementById('course').value = "";
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    });
});
