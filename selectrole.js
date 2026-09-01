document.addEventListener("DOMContentLoaded", function () {

    const roleCards =
        document.querySelectorAll(".role-card");

    const roleInput =
        document.getElementById("roleInput");

    const continueBtn =
        document.getElementById("continueBtn");

    const roleForm =
        document.getElementById("roleForm");


    let selectedRole = "";


    roleCards.forEach(function (card) {

        card.addEventListener("click", function () {

            roleCards.forEach(function (item) {
                item.classList.remove("selected");
            });


            this.classList.add("selected");


            selectedRole =
                this.getAttribute("data-role");


            roleInput.value =
                selectedRole;


            continueBtn.classList.add("enabled");

        });

    });


    if (roleForm) {

        roleForm.addEventListener(
            "submit",
            function (event) {

                if (selectedRole === "") {

                    event.preventDefault();

                    alert(
                        "Please select Client or Freelancer."
                    );

                    return;
                }

                roleInput.value =
                    selectedRole;

            }
        );

    }

});