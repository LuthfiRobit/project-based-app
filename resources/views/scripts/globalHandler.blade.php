<script>
    /**
     * Handles various types of AJAX responses including success, errors, and validation errors.
     * Provides user-friendly feedback using SweetAlert.
     */
    const ResponseHandler = {
        /**
         * Displays a success message with an optional callback.
         * @param {string} message - Success message to display.
         * @param {Function|null} callback - Optional callback function after success.
         */
        handleSuccess: function(message, callback = null) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                if (typeof callback === 'function') callback();
            });
        },

        /**
         * Displays an error message with an optional callback.
         * @param {string} message - Error message to display.
         * @param {Function|null} callback - Optional callback function after error.
         */
        handleError: function(message, callback = null) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message || 'An error occurred.',
                showConfirmButton: true
            }).then(() => {
                if (typeof callback === 'function') callback();
            });
        },

        /**
         * Handles form validation errors by displaying error messages under respective input fields.
         * @param {Object} errors - Object containing validation errors (field => messages).
         * @param {HTMLFormElement|null} form - The form element to update.
         */
        handleValidationErrors: function(errors, form) {
            if (!form) return;

            $(form).find('.is-invalid').removeClass('is-invalid');
            $(form).find('.invalid-feedback').remove();

            $.each(errors, function(field, messages) {
                let input = $(form).find(`[name="${field}"]`);
                if (input.length) {
                    input.addClass('is-invalid');
                    input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                }
            });
        },

        /**
         * Handles JSON responses and updates DOM elements based on response data.
         * @param {Object} response - JSON response from the server.
         * @param {string|null} target - Optional selector to update specific elements.
         */
        handleResponse: function(response, target = null) {
            if (response.status === 200) {
                if (typeof response.data === "string") {
                    $(target ?? 'body').html(response.data);
                } else if (typeof response.data === "object") {
                    $.each(response.data, function(key, value) {
                        let field = $(`[name="${key}"], #${key}`);

                        if (field.is(":checkbox, :radio")) {
                            field.prop('checked', value);
                        } else if (field.is("input, textarea, select")) {
                            if (!field.is("input[type='file']")) {
                                field.val(value).trigger('change');
                            }
                        } else {
                            field.html(value);
                        }
                    });
                }
            } else {
                this.handleError(response.message);
            }
        },

        /**
         * Handles HTTP errors and displays appropriate error messages.
         * @param {Object} xhr - XMLHttpRequest object containing error response.
         * @param {Function|null} errorCallback - Optional callback function for additional error handling.
         */
        handleHttpError: function(xhr, errorCallback = null) {
            let message = 'Server error.';

            switch (xhr.status) {
                case 401:
                    message = 'Unauthorized! Please log in again.';
                    break;
                case 403:
                    message = 'Forbidden! You do not have permission to perform this action.';
                    break;
                case 404:
                    message = 'Not Found! The requested resource does not exist.';
                    break;
                case 500:
                    message = 'Internal Server Error! Please contact support.';
                    break;
                default:
                    if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }
                    break;
            }

            this.handleError(message);

            if (typeof errorCallback === 'function') {
                errorCallback(xhr);
            }
        }
    };

    /**
     * AJAX handler for sending requests with built-in response handling.
     */
    const AjaxHandler = {
        /**
         * Sends an AJAX request with common configurations.
         * @param {string} url - The API endpoint.
         * @param {string} method - HTTP method ('GET', 'POST', etc.).
         * @param {FormData|Object} data - Data payload.
         * @param {Function|null} successCallback - Function to execute on success.
         * @param {Function|null} errorCallback - Function to execute on error.
         * @param {HTMLFormElement|null} form - The form element (for validation handling).
         * @param {Object} headers - Optional headers to include in the request.
         */
        sendRequest: function(url, method, data, successCallback, errorCallback, form = null, headers = {}) {
            $.ajax({
                url: url,
                method: method,
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: headers,
                success: function(response) {
                    if (response.status === 200) {
                        ResponseHandler.handleSuccess(response.message, successCallback);
                    } else if (response.status === 422) {
                        ResponseHandler.handleValidationErrors(response.data, form);
                    } else {
                        ResponseHandler.handleError(response.message);
                    }
                },
                error: function(xhr) {
                    ResponseHandler.handleHttpError(xhr, errorCallback);
                }
            });
        },

        /**
         * Sends a POST request with form data for storing new records.
         */
        sendStoreRequest: function(url, form, successCallback, errorCallback, headers = {}) {
            let formData = new FormData(form);
            this.sendRequest(url, 'POST', formData, successCallback, errorCallback, form, headers);
        },

        /**
         * Sends a PUT request with form data for updating existing records.
         */
        sendUpdateRequest: function(url, form, successCallback, errorCallback, headers = {}) {
            let formData = new FormData(form);
            formData.append('_method', 'PUT');
            this.sendRequest(url, 'POST', formData, successCallback, errorCallback, form, headers);
        },

        /**
         * Sends a GET request and handles the response.
         */
        sendGetRequest: function(url, successCallback, errorCallback) {
            showLoading(); // Show loading animation
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    hideLoading(); // Hide loading animation
                    ResponseHandler.handleResponse(response);
                    if (typeof successCallback === 'function') successCallback(response);
                },
                error: function(xhr) {
                    hideLoading(); // Hide loading animation
                    ResponseHandler.handleHttpError(xhr, errorCallback);
                }
            });
        }
    };
</script>
