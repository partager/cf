$(document).ready(function () {
    $("form").submit(function (e) {
        // Get values from the input fields
        var email = document.querySelector('input[name="email"]');
        var phone = document.querySelector('input[name="phone"]');
        var mobile = document.querySelector('input[name="mobile"]');

        // Check if phone and mobile input fields exist and have non-empty values
        if (phone && phone.value.trim() !== "") {
            // Phone number validation
            var phoneNum = parseInt(phone.value, 10);
            var phoneRegex = /^(\+\d{1,3}[- ]?)?\d{10}$/; // Phone number validation regex for 10 digit number
            if (!phoneRegex.test(phoneNum.toString())) {
                e.preventDefault();
                $(this).find(":submit").after('<br><div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Wrong phone number!</strong> Please enter a valid 10 digit phone number.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                return false;
            }
        }

        if (mobile && mobile.value.trim() !== "") {
            // Mobile number validation
            var mobileNum = parseInt(mobile.value, 10);
            var mobileRegex = /^(\+\d{1,3}[- ]?)?\d{10}$/; // Mobile number validation regex for 10 digit number
            if (!mobileRegex.test(mobileNum.toString())) {
                e.preventDefault();
                $(this).find(":submit").after('<br><div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Wrong mobile number!</strong> Please enter a valid 10 digit mobile number.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                return false;
            }
        }
        if (email && email.value.trim() !== "") {
            // Email validation
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Email validation regex
            if (!emailRegex.test(email.value)) {
                e.preventDefault();
                $(this).find(":submit").after('<br><div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Wrong email!</strong> Please enter a valid email address.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                return false;
            }
        }
    });
});
