let ClassesService = {

    getClasses: function () {
        const classesGrid = document.getElementById("classesGrid");

        // Assuming your backend route is /classes
        fetch("http://localhost/ironforge-gym/backend/classes")
            .then((res) => {
                return res.json();
            })
            .then((data) => {
                classesGrid.innerHTML = "";

                data.forEach((classItem) => {
                    console.log(classItem);

                    classesGrid.innerHTML += `
                    <div class="col-lg-4 col-md-6 class-item" data-category="${classItem.category_id}">
                        <div style="background: #0a0a0a; border: 1px solid #363636; border-radius: 8px; overflow: hidden; margin-bottom: 30px; transition: transform 0.3s;">
                            
                            <!-- UPDATED SECTION: Added padding:0 and margin:0 to force full width -->
                            <div class="" style="height: 240px; width: 100%; padding: 0; margin: 0; border: none;">
                                <img src="${classItem.image_url || './assets/default-class.jpg'}" 
                                     alt="${classItem.title}" 
                                     style="width: 100%; height: 100%; object-fit: cover; display: block; margin: 0; padding: 0;">
                            </div>
                            <!-- UPDATED SECTION END -->

                            <div style="padding: 25px;">
                                <span style="color: #f36100; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                                    ${classItem.categoryName} 
                                </span>
                                <h5 style="color: #ffffff; font-size: 22px; font-weight: 600; margin: 10px 0;">
                                    ${classItem.title}
                                </h5>
                                <p style="color: #c4c4c4; font-size: 14px; margin-bottom: 15px;">
                                    ${classItem.description}
                                </p>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #363636;">
                                    <span style="color: #c4c4c4; font-size: 13px;">
                                        <i class="fa fa-clock-o" style="color: #f36100; margin-right: 5px;"></i> 
                                        ${classItem.duration_minutes} min
                                    </span>
                                    <span style="color: #c4c4c4; font-size: 13px;">
                                        <i class="fa fa-users" style="color: #f36100; margin-right: 5px;"></i> 
                                        Max ${classItem.capacity}
                                    </span>
                                    <a onclick="BookingService.bookClass(${classItem.class_id})" href="#/login" class="nav-link" style="color: #f36100; font-weight: 600;">
                                        BOOK <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                });
            })
            .catch((err) => {
                console.log("Error loading classes:", err);
            });
    }
};