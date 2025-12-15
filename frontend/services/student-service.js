let StudentService = {
    init: function () {
        $("#addStudentForm").validate({
            submitHandler: function (form) {
                var student = Object.fromEntries(new FormData(form).entries());
                StudentService.addStudent(student);
                form.reset();
            }
        });
        $("#editStudentForm").validate({
            submitHandler: function (form) {
                var student = Object.fromEntries(new FormData(form).entries());
                StudentService.editStudent(student);
            }
        });
        StudentService.getAllStudents();
    },
    getAllStudents: function () {
        RestClient.get("students", function (data) {
            Utils.datatable("students-table", [
                { data: "name", title: "Name" },
                { data: "email", title: "Email" },
                { title: "Actions", render: function (data, type, row) {
                    const rowStr = encodeURIComponent(JSON.stringify(row));
                    return `<button onclick="StudentService.openEditModal(${row.id})">Edit</button>
                            <button onclick="StudentService.openConfirmationDialog(decodeURIComponent('${rowStr}'))">Delete</button>`;
                }}
            ], data);
        });
    },
    addStudent: function (student) { RestClient.post("students", student, () => { StudentService.getAllStudents(); }); },
    editStudent: function (student) { RestClient.patch("students/" + student.id, student, () => { StudentService.getAllStudents(); }); },
    deleteStudent: function () { let id = $("#delete_student_id").val(); RestClient.delete("students/" + id, null, () => { StudentService.getAllStudents(); }); },
    openEditModal: function (id) { },
    openConfirmationDialog: function (student) {  },
    closeModal: function () {  }
};
