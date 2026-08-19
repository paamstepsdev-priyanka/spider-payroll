/**
 * Spider Payroll - Global Form Validation & AJAX Form Processing System
 * Built on jQuery Validation, Bootstrap 5, & Flatpickr
 */

/**
 * Universal Toast Notification Helper
 * Displays Bootstrap 5 or SweetAlert2 Toasts dynamically.
 * 
 * @param {string} type - 'success', 'error', 'warning', 'info'
 * @param {string} message - Message text to display
 * @param {string} [title] - Optional title text
 */
function showToast(type, message, title) {
    type = type || 'info';
    title = title || (type.charAt(0).toUpperCase() + type.slice(1));

    // Try SweetAlert2 Toast first if available
    if (typeof Swal !== 'undefined') {
        var iconType = (type === 'error') ? 'error' : (type === 'warning' ? 'warning' : (type === 'info' ? 'info' : 'success'));
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: iconType,
            title: title,
            text: message
        });
        return;
    }

    // Fallback to Bootstrap 5 Toast
    var container = document.getElementById('globalToastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'globalToastContainer';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1090';
        document.body.appendChild(container);
    }

    var bgClass = 'bg-primary';
    if (type === 'success') bgClass = 'bg-success';
    else if (type === 'error') bgClass = 'bg-danger';
    else if (type === 'warning') bgClass = 'bg-warning text-dark';
    else if (type === 'info') bgClass = 'bg-info text-dark';

    var toastId = 'toast-' + Date.now();
    var toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', toastHtml);
    var toastEl = document.getElementById(toastId);
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        var bsToast = new bootstrap.Toast(toastEl, { delay: 4000 });
        bsToast.show();
        toastEl.addEventListener('hidden.bs.toast', function () {
            toastEl.remove();
        });
    } else {
        setTimeout(function () {
            toastEl.remove();
        }, 4000);
    }
}

/**
 * Global Helper to Initialize Standard jQuery Validation with Bootstrap 5
 * 
 * @param {string|HTMLElement} formSelector 
 * @param {Object} rules 
 * @param {Object} [messages] 
 * @returns {Object} jQuery Validation instance
 */
function initFormValidation(formSelector, rules, messages) {
    var $form = $(formSelector);
    if (!$form.length) return null;

    return $form.validate({
        rules: rules || {},
        messages: messages || {},
        errorElement: 'div',
        errorClass: 'invalid-feedback d-block',
        highlight: function (element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.next('.select2-container').length) {
                error.insertAfter(element.next('.select2-container'));
            } else if (element.parent('.input-group').length) {
                error.insertAfter(element.parent('.input-group'));
            } else if (element.prop('type') === 'radio' || element.prop('type') === 'checkbox') {
                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
}

/**
 * Universal AJAX Form Initializer with jQuery Validation, Loading State, & Server Error Handling
 * 
 * @param {string|HTMLElement} formSelector 
 * @param {Object} options - Configuration options
 * @param {Object} options.rules - Validation rules
 * @param {Object} [options.messages] - Validation error messages
 * @param {Function} [options.onSuccess] - Custom success callback
 * @param {Function} [options.onError] - Custom error callback
 * @returns {Object} jQuery Validation instance
 */
function initAjaxForm(formSelector, options) {
    options = options || {};
    var $form = $(formSelector);
    if (!$form.length) return null;

    var validator = $form.validate({
        rules: options.rules || {},
        messages: options.messages || {},
        errorElement: 'div',
        errorClass: 'invalid-feedback d-block',
        highlight: function (element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.next('.select2-container').length) {
                error.insertAfter(element.next('.select2-container'));
            } else if (element.parent('.input-group').length) {
                error.insertAfter(element.parent('.input-group'));
            } else if (element.prop('type') === 'radio' || element.prop('type') === 'checkbox') {
                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        invalidHandler: function(event, validatorInstance) {
            if (typeof options.onError === 'function') {
                options.onError(validatorInstance);
            }
        },
        submitHandler: function (form) {
            var $submitBtn = $(form).find('button[type="submit"]');
            var originalBtnHtml = $submitBtn.html();

            // Disable submit button & show loading state
            $submitBtn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...'
            );

            var formData = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method') || 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    if (response.status === 'success') {
                        showToast('success', response.message || 'Operation completed successfully.');
                        
                        if (typeof options.onSuccess === 'function') {
                            options.onSuccess(response);
                        } else if (response.redirect) {
                            setTimeout(function () {
                                window.location.href = response.redirect;
                            }, 1000);
                        } else {
                            $submitBtn.prop('disabled', false).html(originalBtnHtml);
                        }
                    } else {
                        $submitBtn.prop('disabled', false).html(originalBtnHtml);
                        
                        // Map server-side validation errors back to fields if provided
                        if (response.errors && typeof response.errors === 'object') {
                            validator.showErrors(response.errors);
                        }
                        
                        showToast('error', response.message || 'An error occurred during submission.');
                        
                        if (typeof options.onError === 'function') {
                            options.onError(response);
                        }
                    }
                },
                error: function (xhr) {
                    $submitBtn.prop('disabled', false).html(originalBtnHtml);
                    var errorMsg = 'An unexpected server error occurred. Please try again.';
                    
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors && typeof xhr.responseJSON.errors === 'object') {
                            validator.showErrors(xhr.responseJSON.errors);
                        }
                        if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                    }
                    
                    showToast('error', errorMsg);
                    
                    if (typeof options.onError === 'function') {
                        options.onError(xhr);
                    }
                }
            });

            return false; // Prevent standard form submit
        }
    });

    return validator;
}

// Register Custom Validation Methods
if (typeof $.validator !== 'undefined') {
    // Indian Mobile Number
    $.validator.addMethod("indianPhone", function (value, element) {
        return this.optional(element) || /^[6-9]\d{9}$/.test(value);
    }, "Please enter a valid 10-digit Indian phone number starting with 6-9.");

    // IFSC Code
    $.validator.addMethod("ifscCode", function (value, element) {
        return this.optional(element) || /^[A-Z]{4}0[A-Z0-9]{6}$/i.test(value);
    }, "Please enter a valid 11-character IFSC code (e.g. HDFC0001234).");

    // PAN Card Number
    $.validator.addMethod("panCard", function (value, element) {
        return this.optional(element) || /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i.test(value);
    }, "Please enter a valid 10-character PAN Card number (e.g. ABCDE1234F).");

    // Aadhaar Number
    $.validator.addMethod("aadhaarNumber", function (value, element) {
        var cleanVal = value.replace(/\s+/g, '');
        return this.optional(element) || /^\d{12}$/.test(cleanVal);
    }, "Please enter a valid 12-digit Aadhaar number.");

    // Valid Date
    $.validator.addMethod("validDate", function (value, element) {
        return this.optional(element) || !isNaN(Date.parse(value));
    }, "Please enter a valid date.");
}
