let TrainersService = {

    getTrainers: function () {

        const trainers_div = document.getElementById("trainers-div");

        fetch("http://localhost/ironforge-gym/backend/trainers")
            .then((res) => {
                return res.json();
            })
            .then((data) => {


                trainers_div.innerHTML = "";

                data.forEach((trainer) => {

                    console.log(trainer)

                trainers_div.innerHTML += `
                       <div class="col-lg-4 col-md-6">
                    <div class="ts-item" style="margin-bottom: 30px; position: relative; overflow: hidden; width: 100%; height: 400px;">
                        <img src="${trainer.image_url}" alt="David Martinez" style="width: 100%; height: 100%; object-fit: cover; object-position: center 20%; display: block;">
                        <div class="ts_text" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.8); padding: 20px; color: white;">
                            <h4 style="margin: 0 0 5px 0; color: white;">${trainer.full_name}</h4>
                            <span style="color: #c4c4c4; font-size: 14px;">${trainer.specialization}</span>
                            <div class="tt_social" style="transform: skewY(5deg); margin-top: 13px;">
                                <a href="#" style="font-size: 14px; color: #c4c4c4; margin-right: 10px;"><i class="fa fa-facebook"></i></a>
                                <a href="#" style="font-size: 14px; color: #c4c4c4; margin-right: 10px;"><i class="fa fa-twitter"></i></a>
                                <a href="#" style="font-size: 14px; color: #c4c4c4;"><i class="fa fa-instagram"></i></a>
                            </div>
                            <p style="color: #c4c4c4; font-size: 13px; margin-top: 15px; margin: 15px 0 0 0;">${trainer.description}</p>
                        </div>
                    </div>
                </div>
                `
                })
            })
            .catch((err) => {
                console.log(err)
            })

    }
}