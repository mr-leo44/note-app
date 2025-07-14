document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[id^="editPromotionModal-"]').forEach(function(modal) {
        const id = modal.id.replace('editPromotionModal-', '');
        const facultySelect = document.getElementById('faculty_id-' + id);
        const departmentSelect = document.getElementById('department_id-' + id);
        if (!facultySelect || !departmentSelect) return;
        const allDepartments = JSON.parse(departmentSelect.dataset.departments || '[]');
        function filterDepartments() {
            const facultyId = facultySelect.value;
            departmentSelect.innerHTML = '<option value="">Sélectionner un département</option>';
            allDepartments.filter(dep => dep.faculty_id == facultyId).forEach(dep => {
                const option = document.createElement('option');
                option.value = dep.id;
                option.textContent = dep.name;
                departmentSelect.appendChild(option);
            });
            // Rétablir la sélection si possible
            const oldDepartmentId = departmentSelect.getAttribute('data-old');
            if (oldDepartmentId) {
                departmentSelect.value = oldDepartmentId;
            }
        }
        facultySelect.addEventListener('change', filterDepartments);
        // Initialisation
        if (facultySelect.value) {
            filterDepartments();
        }
    });
});
