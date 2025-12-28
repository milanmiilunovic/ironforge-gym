let BookingService = {


    bookClass: function(classId) { // Pass the class_id as an argument
        const token = localStorage.getItem('user_token');
        const parsedToken = Utils.parseJwt(token);
        const userId = parsedToken.user.user_id;

        // Prepare the data to match the curl -d "..."
        const payload = {
            user_id: userId,
            class_id: classId
        };

        fetch(Constants.PROJECT_BASE_URL + "bookings", {
            method: "POST",
            headers: {
                "Content-Type": "application/json" // Crucial: Matches -H "Content-Type: application/json"
            },
            body: JSON.stringify(payload) // Crucial: Matches the -d data
        })
            .then((res) => {
                return res.json();
            })
            .then((data) => {
                alert('Booking made successfully!');
                console.log(data); // Logs the result
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    },



    initializeUserBookings: function () {
        const token = localStorage.getItem('user_token');
        if (!token) return;

        const parsedToken = Utils.parseJwt(token);

        const userId = parsedToken.user.user_id;


        const bookingsList = document.getElementById("bookingsList");
        const emptyState = document.getElementById("emptyState");

        fetch(Constants.PROJECT_BASE_URL + "users/" + userId + "/bookings")
            .then((res) => res.json())
            .then((data) => {
                bookingsList.innerHTML = "";

                if (!data || data.length === 0) {
                    emptyState.style.display = "block";
                    return;
                }

                emptyState.style.display = "none";

                data.forEach((booking) => {

                    console.log(booking)

                    // 1. Format the Date (e.g., "2025-10-18 17:00:00" -> "18", "OCT 2025", "5:00 PM")
                    const dateObj = new Date(booking.booking_date);
                    const day = dateObj.getDate();
                    const monthYear = dateObj.toLocaleString('default', { month: 'short' }).toUpperCase() + " " + dateObj.getFullYear();
                    const time = dateObj.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                    // 2. Status styling logic
                    const isCancelled = booking.status === 'cancelled';
                    const statusText = isCancelled ? 'CANCELLED' : 'CONFIRMED';
                    const statusBg = isCancelled ? 'rgba(255, 68, 68, 0.1)' : 'rgba(76, 175, 80, 0.1)';
                    const statusColor = isCancelled ? '#ff4444' : '#4caf50';
                    const borderColor = isCancelled ? '#363636' : '#f36100';

                    bookingsList.innerHTML += `
<div class="col-lg-12 booking-item">
    <div style="background: #0a0a0a; padding: 25px; border-radius: 8px; border: 1px solid #363636; border-left: 4px solid ${borderColor}; margin-bottom: 20px; ${isCancelled ? 'opacity: 0.6;' : ''}">
        <div class="row align-items-center">
            
            <!-- 1. Date Section -->
            <div class="col-lg-2 col-md-3">
                <div style="text-align: center; padding: 15px; background: #151515; border-radius: 5px;">
                    <div style="color: ${borderColor}; font-size: 32px; font-weight: 700; line-height: 1;">${day}</div>
                    <div style="color: #c4c4c4; font-size: 14px; margin-top: 5px;">${monthYear}</div>
                    <div style="color: #ffffff; font-size: 16px; font-weight: 600; margin-top: 5px;">${time}</div>
                </div>
            </div>

            <!-- 2. Details Section (Status moved here for better alignment) -->
            <div class="col-lg-7 col-md-6" style="padding-left: 20px;">
                <h4 style="color: #ffffff; font-weight: 600; margin-bottom: 8px;">${booking.title}</h4>
                
                <p style="color: #c4c4c4; font-size: 14px; margin-bottom: 6px;">
                    <i class="fa fa-user" style="color: #f36100; margin-right: 5px;"></i>
                    Trainer: ${booking.full_name || 'Staff'}
                </p>
                
                <p style="color: #c4c4c4; font-size: 14px; margin-bottom: 12px;">
                    <i class="fa fa-clock-o" style="color: #f36100; margin-right: 5px;"></i>
                    Duration: ${booking.duration_minutes || '60'} minutes
                </p>

                <!-- Status Badge aligned left with text -->
                <div>
                    <span style="display: inline-block; background: ${statusBg}; color: ${statusColor}; padding: 6px 14px; border-radius: 4px; font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">
                        ${statusText}
                    </span>
                </div>
            </div>
            
            <!-- 3. Action Section (Button pushed to right) -->
            <div class="col-lg-3 col-md-3" style="display: flex; justify-content: flex-end; align-items: center;">
                ${!isCancelled ? `
                    <button onclick="BookingService.cancelBooking(${booking.booking_id})" style="background: transparent; color: #ff4444; padding: 10px 30px; border: 1px solid #ff4444; border-radius: 5px; cursor: pointer; font-weight: 600; font-size: 14px; transition: 0.3s;">
                        CANCEL
                    </button>
                ` : ''}
            </div>

        </div>
    </div>
</div>
`;
                });
            })
            .catch((err) => {
                console.error("Error loading bookings:", err);
            });
    },

    cancelBooking: function(bookingId) {

        console.log("Test:", bookingId)

        if (confirm('Are you sure you want to cancel this booking?')) {
            fetch(Constants.PROJECT_BASE_URL + "bookings/" + bookingId, {
                method: 'DELETE'

            })
                .then(res => res.json())
                .then(data => {
                    alert('Booking cancelled successfully!');
                    this.initializeUserBookings(); // Refresh the list
                })
                .catch(err => console.error(err));
        }
    }
}