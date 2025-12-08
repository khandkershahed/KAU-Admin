
/* ------------------------------------------------------------
   SELECT2 (safe)
------------------------------------------------------------ */
$(document).ready(function () {
    if ($('.js-example-basic-multiple').length || $('.js-example-basic-single').length) {
        $('.js-example-basic-multiple, .js-example-basic-single').select2();
    }
});


/* ------------------------------------------------------------
   SELECT ALL CHECKBOXES
------------------------------------------------------------ */
$('.metronic_select_all').on('change', function () {
    $('[type="checkbox"]').prop('checked', $(this).prop('checked'));
});


/* ------------------------------------------------------------
   PASSWORD SHOW / HIDE TOGGLE
------------------------------------------------------------ */
$(document).ready(function () {
    $('.toggle-password').click(function () {
        const passwordInput = $(this).closest('.position-relative').find('input');
        if (!passwordInput.length) return;

        const isVisible = passwordInput.attr('type') === 'text';
        passwordInput.attr('type', isVisible ? 'password' : 'text');

        $(this).find('.bi-eye').toggleClass('d-none');
        $(this).find('.bi-eye-slash').toggleClass('d-none');
    });
});


/* ------------------------------------------------------------
   PASSWORD METER (SAFE VERSION)
------------------------------------------------------------ */
function passwordMeter(inputElement, highlightElement, options) {

    if (!inputElement || !highlightElement) return; // <-- safety check

    var score = 0;
    var checkSteps = 0;

    var check = function () {
        var total = 0;
        var eachStep = stepValue();

        if (hasMinLength()) total += eachStep;
        if (options.checkUppercase && hasUppercase()) total += eachStep;
        if (options.checkLowercase && hasLowercase()) total += eachStep;
        if (options.checkDigit && hasDigit()) total += eachStep;
        if (options.checkChar && hasSpecial()) total += eachStep;

        score = total;
        updateUI();
    };

    var hasMinLength = () => inputElement.value.length >= options.minLength;
    var hasUppercase = () => /[A-Z]/.test(inputElement.value);
    var hasLowercase = () => /[a-z]/.test(inputElement.value);
    var hasDigit = () => /[0-9]/.test(inputElement.value);
    var hasSpecial = () => /[~`!#@$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/.test(inputElement.value);

    var stepValue = function () {
        var total = 1;
        if (options.checkUppercase) total++;
        if (options.checkLowercase) total++;
        if (options.checkDigit) total++;
        if (options.checkChar) total++;

        checkSteps = total;
        return 100 / total;
    };

    var updateUI = function () {
        var bars = highlightElement.querySelectorAll("div");
        if (!bars.length) return;

        let each = stepValue();
        bars.forEach(function (bar, index) {
            (each * (index + 1)) <= score
                ? bar.classList.add("active")
                : bar.classList.remove("active");
        });
    };

    check();
    return { check };
}


/* INITIALIZE PASSWORD METER SAFELY */
$(document).ready(function () {
    var inputElement = document.querySelector('.password_input');
    var highlightElement = document.querySelector('.d-flex[data-kt-password-meter-control="highlight"]');

    if (inputElement && highlightElement) {
        var meter = passwordMeter(inputElement, highlightElement, {
            minLength: 8,
            checkUppercase: true,
            checkLowercase: true,
            checkDigit: true,
            checkChar: true
        });

        inputElement.addEventListener('input', () => meter.check());
    }
});


/* ------------------------------------------------------------
   DELETE ACTION (SweetAlert)
------------------------------------------------------------ */
$(document).on('click', '.delete', function (e) {
    e.preventDefault();

    const deleteUrl = $(this).attr('href');
    if (!deleteUrl) return;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-success'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    Swal.fire('Deleted!', 'Record removed successfully.', 'success')
                        .then(() => location.reload());
                },
                error: function () {
                    Swal.fire('Error', 'Failed to delete. Try again.', 'error');
                }
            });
        }
    });
});


/* ------------------------------------------------------------
   DELETE ACCOUNT (SweetAlert + Password Check)
------------------------------------------------------------ */
$(document).on('click', '.delete-account', async function (e) {
    e.preventDefault();

    const deleteAccountUrl = $(this).attr('href');
    const checkPasswordUrl = $(this).data('check-password-url');

    if (!deleteAccountUrl || !checkPasswordUrl) return;

    const { value: password } = await Swal.fire({
        title: "Confirm Password",
        input: "password",
        inputPlaceholder: "Enter your password",
        showCancelButton: true,
        confirmButtonText: 'Delete Account',
        cancelButtonText: 'Cancel',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-success'
        }
    });

    if (!password) return;

    $.ajax({
        url: checkPasswordUrl,
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { password },

        success: function (response) {
            if (response.success) {
                $.ajax({
                    url: deleteAccountUrl,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

                    success: function () {
                        Swal.fire('Deleted!', 'Your account has been removed.', 'success')
                            .then(() => window.location.href = '/');
                    }
                });
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },

        error: function () {
            Swal.fire('Error', 'Password check failed.', 'error');
        }
    });
});


/* ------------------------------------------------------------
   TOGGLE STATUS (GLOBAL FUNCTION)
------------------------------------------------------------ */
function toggleStatus(route, id) {
    if (!route || !id) return;

    const csrf = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: route,
        type: "POST",
        headers: { 'X-CSRF-TOKEN': csrf },

        success: function (response) {
            $(`#status_toggle_${id}`).prop('checked', response.success);

            Swal.fire({
                toast: true,
                icon: 'success',
                title: 'Updated successfully',
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });
        },

        error: function () {
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Failed to update',
                position: 'top-end'
            });
        }
    });
}
