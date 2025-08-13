document.addEventListener('DOMContentLoaded', function () {
    const facultySelect = document.getElementById('faculty_id');
    const departmentSelect = document.getElementById('department_id');
    if (!facultySelect || !departmentSelect) return;

    // Store all departments in a data attribute as JSON
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
    }

    facultySelect.addEventListener('change', filterDepartments);

    // On page load, filter if a faculty is already selected (for validation error)
    if (facultySelect.value) {
        filterDepartments();
        // If old department_id exists, select it
        const oldDepartmentId = departmentSelect.getAttribute('data-old');
        if (oldDepartmentId) {
            departmentSelect.value = oldDepartmentId;
        }
    }    
});
