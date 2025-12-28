let AdminService = {
    originalZIndex: '',

    init: function() {
        // Fix for header overlay issue
        const header = document.querySelector('.header-section');
        if (header) {
            this.originalZIndex = header.style.zIndex;
            header.style.zIndex = '0';
        }

        // Check if the user is an admin before initializing
        const user = UserService.getCurrentUser();
        if (!user || user.role !== 'admin') {
            // Redirect to login or show an error if not an admin
            window.location.hash = '#/login';
            return;
        }

        this.loadTrainers();
        this.loadClasses();
        this.loadCategories();
        this.setupEventListeners();
    },

    loadCategories: function() {
        const url = Constants.PROJECT_BASE_URL + "categories";
        console.log("Loading categories from URL:", url);
        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer ' + UserService.getToken());
            },
            success: function(data) {
                console.log("Categories data received:", data);
                let categoryOptions = '<option value="">Select Category</option>';
                if (data && Array.isArray(data)) {
                    data.forEach(function(category) {
                        categoryOptions += `<option value="${category.category_id}">${category.name}</option>`;
                    });
                } else {
                    console.warn("Categories data is not an array or is empty:", data);
                }
                console.log("Generated category options HTML:", categoryOptions);
                
                const $categorySelect = $("#class-category-select");
                console.log("jQuery selector for #class-category-select found:", $categorySelect.length, "element(s).");
                if ($categorySelect.length > 0) {
                    $categorySelect.html(categoryOptions);
                    console.log("HTML of #class-category-select after populating:", $categorySelect.html());
                } else {
                    console.error("#class-category-select not found in the DOM when trying to populate it.");
                }
            },
            error: function(xhr) {
                console.error("Failed to load categories:", xhr);
            }
        });
    },

    destroy: function() {
        const header = document.querySelector('.header-section');
        if (header) {
            header.style.zIndex = this.originalZIndex;
        }
    },

    loadTrainers: function() {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "trainers",
            type: "GET",
            success: function(data) {
                let trainersHtml = "";
                data.forEach(function(trainer) {
                    trainersHtml += `
                        <tr>
                            <td>${trainer.trainer_id}</td>
                            <td>${trainer.full_name}</td>
                            <td>${trainer.specialization}</td>
                            <td>${trainer.email}</td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="AdminService.openTrainerModal(${trainer.trainer_id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="AdminService.deleteTrainer(${trainer.trainer_id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $("#trainers-table-body").html(trainersHtml);

                // Populate trainer select in class modal
                let trainerOptions = '<option value="">Select Trainer</option>';
                data.forEach(function(trainer) {
                    trainerOptions += `<option value="${trainer.trainer_id}">${trainer.full_name}</option>`;
                });
                $("#class-trainer-select").html(trainerOptions);

            },
            error: function(xhr, status, error) {
                console.error("Failed to load trainers:", error);
            }
        });
    },

    loadClasses: function() {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "classes",
            type: "GET",
            success: function(data) {
                let classesHtml = "";
                data.forEach(function(classInfo) {
                    classesHtml += `
                        <tr>
                            <td>${classInfo.class_id}</td>
                            <td>${classInfo.title}</td>
                            <td>${classInfo.trainer_name || 'N/A'}</td>
                            <td>${new Date(classInfo.schedule_time).toLocaleString()}</td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="AdminService.openClassModal(${classInfo.class_id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="AdminService.deleteClass(${classInfo.class_id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $("#classes-table-body").html(classesHtml);
            },
            error: function(xhr, status, error) {
                console.error("Failed to load classes:", error);
            }
        });
    },

    setupEventListeners: function() {
        console.log("Setting up event listeners.");
        // Handle trainer form submission using event delegation
        $(document).off('submit', '#trainer-form').on('submit', '#trainer-form', function(e) {
            e.preventDefault();
            AdminService.saveTrainer();
        });

        // Handle class form submission using event delegation
        $(document).off('submit', '#class-form').on('submit', '#class-form', function(e) {
            e.preventDefault();
            AdminService.saveClass();
        });

        // Clear modal on hide
        $('#trainer-modal').on('hidden.bs.modal', function () {
            $('#trainer-form')[0].reset();
            $('#trainer-id').val('');
        });

        $('#class-modal').on('hidden.bs.modal', function () {
            $('#class-form')[0].reset();
            $('#class-id').val('');
        });
    },

    openTrainerModal: function(trainerId) {
        console.log("openTrainerModal called with trainerId:", trainerId);
        if (trainerId) {
            // Edit mode
            $.ajax({
                url: Constants.PROJECT_BASE_URL + "trainers/" + trainerId,
                type: "GET",
                success: function(trainer) {
                    console.log("Trainer data received for editing:", trainer);
                    $("#trainer-id").val(trainer.trainer_id);
                    $("#trainer-name").val(trainer.full_name);
                    $("#trainer-specialization").val(trainer.specialization);
                    $("#trainer-email").val(trainer.email);
                    $("#trainer-phone").val(trainer.phone);
                    $("#trainer-experience").val(trainer.experience_years);
                    console.log("Values after setting:");
                    console.log("trainer-id:", $("#trainer-id").val());
                    console.log("trainer-name:", $("#trainer-name").val());
                    console.log("trainer-specialization:", $("#trainer-specialization").val());
                    console.log("trainer-email:", $("#trainer-email").val());
                    console.log("trainer-phone:", $("#trainer-phone").val());
                    console.log("trainer-experience:", $("#trainer-experience").val());
                    $("#trainer-modal").modal('show');
                },
                error: function(xhr) {
                    console.error("Error fetching trainer data:", xhr);
                }
            });
        } else {
            // Add mode
            console.log("Opening trainer modal in Add mode");
            $('#trainer-form')[0].reset();
            $('#trainer-id').val('');
            $("#trainer-modal").modal('show');
        }
    },

    openClassModal: function(classId) {
        if (classId) {
            // Edit mode
            $.ajax({
                url: Constants.PROJECT_BASE_URL + "classes/" + classId,
                type: "GET",
                success: function(classInfo) {
                    $("#class-id").val(classInfo.class_id);
                    $("#class-title").val(classInfo.title);
                    $("#class-description").val(classInfo.description);
                    $("#class-trainer-select").val(classInfo.trainer_id);
                    $("#class-category-select").val(classInfo.category_id);
                    // Format schedule_time for datetime-local input
                    const schedule = new Date(classInfo.schedule_time).toISOString().slice(0, 16);
                    $("#class-schedule").val(schedule);
                    $("#class-capacity").val(classInfo.capacity);
                    $("#class-modal").modal('show');
                }
            });
        } else {
            // Add mode
            $('#class-form')[0].reset();
            $('#class-id').val('');
            $("#class-modal").modal('show');
        }
    },

    saveTrainer: function() {
        const trainerId = $("#trainer-id").val();
        const trainerData = {
            full_name: $("#trainer-name").val(),
            specialization: $("#trainer-specialization").val(),
            email: $("#trainer-email").val(),
            phone: $("#trainer-phone").val(),
            experience_years: $("#trainer-experience").val()
        };

        const url = trainerId ? Constants.PROJECT_BASE_URL + "admin/trainers/" + trainerId : Constants.PROJECT_BASE_URL + "admin/trainers";
        const type = trainerId ? "PUT" : "POST";

        console.log("Saving trainer with ID:", trainerId);
        console.log("Trainer data:", trainerData);
        console.log("Request URL:", url);
        console.log("Request type:", type);

        $.ajax({
            url: url,
            type: type,
            data: JSON.stringify(trainerData),
            contentType: "application/json",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer ' + UserService.getToken());
            },
            success: function() {
                $("#trainer-modal").modal('hide');
                AdminService.loadTrainers();
            },
            error: function(xhr) {
                console.error('Error saving trainer:', xhr.responseJSON);
                alert('Error saving trainer: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Unknown error"));
            }
        });
    },

        saveClass: function() {
            const classId = $("#class-id").val();
            const classData = {
                title: $("#class-title").val(),
                description: $("#class-description").val(),
                trainer_id: $("#class-trainer-select").val(),
                category_id: $("#class-category-select").val(),
                schedule_time: $("#class-schedule").val(),
                capacity: $("#class-capacity").val()
            };
    
            const url = classId ? Constants.PROJECT_BASE_URL + "admin/classes/" + classId : Constants.PROJECT_BASE_URL + "admin/classes";
            const type = classId ? "PUT" : "POST";
            
            $.ajax({
                url: url,
                type: type,
                data: JSON.stringify(classData),
                contentType: "application/json",
                 beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + UserService.getToken());
                },
                success: function() {
                    $("#class-modal").modal('hide');
                    AdminService.loadClasses();
                },
                error: function(xhr) {
                    let errorMessage = "An unknown error occurred.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    console.error('Error saving class:', xhr);
                    alert('Error saving class: ' + errorMessage);
                }
            });
        },
    deleteTrainer: function(trainerId) {
        if (confirm("Are you sure you want to delete this trainer?")) {
            $.ajax({
                url: Constants.PROJECT_BASE_URL + "admin/trainers/" + trainerId,
                type: "DELETE",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + UserService.getToken());
                },
                success: function() {
                    AdminService.loadTrainers();
                },
                error: function(xhr) {
                   confirm("We cannot delete this trainer because they already have an active course assigned to them. Please fire them after they are finished.")
                }
            });
        }
    },

    deleteClass: function(classId) {
        if (confirm("Are you sure you want to delete this class?")) {
            $.ajax({
                url: Constants.PROJECT_BASE_URL + "admin/classes/" + classId,
                type: "DELETE",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + UserService.getToken());
                },
                success: function() {
                    AdminService.loadClasses();
                },
                error: function(xhr) {
                    alert('Error deleting class: ' + xhr.responseJSON.message);
                }
            });
        }
    }
};

// Listen for navigation away from the admin page to clean up styles
$(document).ready(function() {
    $(window).on('hashchange', function() {
        // We only care about when the hash is NOT #/admin, to trigger cleanup.
        if (window.location.hash !== '#/admin') {
            AdminService.destroy();
        }
    });
});
