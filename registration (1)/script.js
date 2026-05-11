function toggleSession(day, type) {
    const cmorning = document.getElementById("cmorning" + day);
    const cafternoon = document.getElementById("cafternoon" + day);
    const lcmorning = document.getElementById("lcmorning" + day);
    const lcafternoon = document.getElementById("lcafternoon" + day);

    const fsession = document.getElementById("fsession" + day);
    const lfsession = document.getElementById("lfsession" + day);

    if (type === 'custom') {
        // Show morning & afternoon
        cmorning.style.display = "block";
        cafternoon.style.display = "block";
        lcmorning.style.display = "block";
        lcafternoon.style.display = "block";

        // Hide full day
        fsession.style.display = "none";
        lfsession.style.display = "none";
        fsession.selectedIndex = 0; // reset
    }

    if (type === 'full') {
        // Show full day
        fsession.style.display = "block";
        lfsession.style.display = "block";

        // Hide custom dropdowns
        cmorning.style.display = "none";
        cafternoon.style.display = "none";
        lcmorning.style.display = "none";
        lcafternoon.style.display = "none";
        cmorning.selectedIndex = 0;
        cafternoon.selectedIndex = 0;
    }
}
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("Form");

    form.addEventListener("submit", function (e) {

        let isValid = true;

        // Remove old errors
        document.querySelectorAll(".error").forEach(el => el.remove());
        const formErrorsDiv = document.getElementById('formErrors');
        if (formErrorsDiv) formErrorsDiv.innerHTML = '';

        const errorMessages = [];

        function showError(input, message) {
            const div = document.createElement("div");
            div.className = "error text-danger mt-1";
            div.innerText = message;

            input.parentElement.appendChild(div); // ✅ always exists
            if (message && message.trim() !== '') errorMessages.push(message);
        }

        function showRadioError(container, message) {
            const div = document.createElement("div");
            div.className = "error text-danger mt-1";
            div.innerText = message;
            container.appendChild(div);
            if (message && message.trim() !== '') errorMessages.push(message);
        }


        const fname = document.getElementById("fname");
        const mobile = document.getElementById("mobile");
        const email = document.getElementById("email");
        const city = document.getElementById("city");
        const pincode = document.getElementById("pincode");

        const attend16 = document.querySelector('input[name="gala_dinner"]:checked');



        // Full Name
        if (fname.value.trim() === "") {
            showError(fname, "Full name is required");
            isValid = false;
        }

        // Mobile
        if (!/^\d{10}$/.test(mobile.value)) {
            showError(mobile, "Enter a valid 10-digit mobile number");
            isValid = false;
        }

        // Email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            showError(email, "Enter a valid email address");
            isValid = false;
        }

        // City
        if (city.value.trim() === "") {
            showError(city, "City is required");
            isValid = false;
        }

        // Pincode
        if (!/^\d{6}$/.test(pincode.value)) {
            showError(pincode, "Enter a valid 6-digit pincode");
            isValid = false;
        }

        // Attend 16th Day - Now check at least one session or gala
        const hasGala = document.querySelector('input[name="gala_dinner"]:checked');
        const hasSession = Array.from(document.querySelectorAll('select[name^="day"]')).some(sel => sel.value !== "");

        if (!hasGala && !hasSession) {
            showRadioError(
                document.querySelector('input[name="gala_dinner"]').parentElement,
                "At least one session or Gala Dinner must be selected"
            );
            isValid = false;
        }

        


        // Prevent submit if invalid
        if (!isValid) {
            e.preventDefault();
            e.stopImmediatePropagation();
            // Render top-level error box below title
            if (formErrorsDiv && errorMessages.length > 0) {
                const box = document.createElement('div');
                box.className = 'alert alert-danger';
                const ul = document.createElement('ul');
                errorMessages.forEach(msg => {
                    const li = document.createElement('li');
                    li.innerText = msg;
                    ul.appendChild(li);
                });
                box.appendChild(ul);
                formErrorsDiv.appendChild(box);
            }
        }


    });
});



