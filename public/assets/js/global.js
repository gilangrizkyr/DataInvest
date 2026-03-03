/**
 * Global JavaScript for DataInvest Application
 * Utility functions and common handlers
 */

// ============================================================
// NOTIFICATION SYSTEM
// ============================================================

/**
 * Show success notification
 * @param {string} message - The message to display
 * @param {string} title - Optional title
 */
function showSuccess(message, title = 'Success') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        timer: 3000,
        timerProgressBar: true,
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
    });
}

/**
 * Show error notification
 * @param {string} message - The message to display
 * @param {string} title - Optional title
 */
function showError(message, title = 'Error') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        timer: 5000,
        timerProgressBar: true,
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
    });
}

/**
 * Show warning notification
 * @param {string} message - The message to display
 * @param {string} title - Optional title
 */
function showWarning(message, title = 'Warning') {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        timer: 4000,
        timerProgressBar: true,
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
    });
}

/**
 * Show info notification
 * @param {string} message - The message to display
 * @param {string} title - Optional title
 */
function showInfo(message, title = 'Information') {
    Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        timer: 3000,
        timerProgressBar: true,
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
    });
}

/**
 * Show confirmation dialog
 * @param {string} message - Confirmation message
 * @param {function} onConfirm - Callback if confirmed
 * @param {function} onCancel - Optional callback if cancelled
 */
function showConfirm(message, onConfirm, onCancel = null) {
    Swal.fire({
        icon: 'question',
        title: 'Confirm Action',
        text: message,
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirm === 'function') {
            onConfirm();
        } else if (!result.isConfirmed && typeof onCancel === 'function') {
            onCancel();
        }
    });
}

// ============================================================
// FORM UTILITIES
// ============================================================

/**
 * Validate email format
 * @param {string} email - Email to validate
 * @returns {boolean}
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Validate required field
 * @param {string} value - Value to validate
 * @returns {boolean}
 */
function validateRequired(value) {
    return value && value.trim().length > 0;
}

/**
 * Get form data as object
 * @param {HTMLFormElement} form - Form element
 * @returns {Object}
 */
function getFormData(form) {
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        if (data[key] === undefined) {
            data[key] = value;
        } else if (Array.isArray(data[key])) {
            data[key].push(value);
        } else {
            data[key] = [data[key], value];
        }
    }
    
    return data;
}

/**
 * Clear form validation states
 * @param {HTMLFormElement} form - Form to clear
 */
function clearFormValidation(form) {
    form.querySelectorAll('.form-control').forEach(field => {
        field.classList.remove('is-invalid', 'is-valid');
    });
}

/**
 * Display form validation errors
 * @param {Object} errors - Error object with field names as keys
 * @param {HTMLFormElement} form - Form element
 */
function displayFormErrors(errors, form) {
    clearFormValidation(form);
    
    Object.keys(errors).forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.classList.add('is-invalid');
            const feedbackEl = document.createElement('div');
            feedbackEl.className = 'invalid-feedback d-block';
            feedbackEl.textContent = Array.isArray(errors[fieldName]) 
                ? errors[fieldName][0] 
                : errors[fieldName];
            
            if (field.nextElementSibling?.classList.contains('invalid-feedback')) {
                field.nextElementSibling.replaceWith(feedbackEl);
            } else {
                field.parentNode.appendChild(feedbackEl);
            }
        }
    });
}

// ============================================================
// API UTILITIES
// ============================================================

/**
 * Make API request
 * @param {string} url - API endpoint
 * @param {Object} options - Fetch options
 * @returns {Promise}
 */
async function apiRequest(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    };

    const finalOptions = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers,
        },
    };

    try {
        const response = await fetch(url, finalOptions);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return { success: true, data };
    } catch (error) {
        console.error('API Error:', error);
        return { success: false, error: error.message };
    }
}

/**
 * Make GET request
 * @param {string} url - API endpoint
 * @returns {Promise}
 */
function apiGet(url) {
    return apiRequest(url, { method: 'GET' });
}

/**
 * Make POST request
 * @param {string} url - API endpoint
 * @param {Object} data - Request body
 * @returns {Promise}
 */
function apiPost(url, data) {
    return apiRequest(url, {
        method: 'POST',
        body: JSON.stringify(data),
    });
}

/**
 * Make PUT request
 * @param {string} url - API endpoint
 * @param {Object} data - Request body
 * @returns {Promise}
 */
function apiPut(url, data) {
    return apiRequest(url, {
        method: 'PUT',
        body: JSON.stringify(data),
    });
}

/**
 * Make DELETE request
 * @param {string} url - API endpoint
 * @returns {Promise}
 */
function apiDelete(url) {
    return apiRequest(url, { method: 'DELETE' });
}

// ============================================================
// DOM UTILITIES
// ============================================================

/**
 * Add/Remove class from element
 * @param {HTMLElement} element - Target element
 * @param {string} className - Class name
 * @param {boolean} add - Add or remove
 */
function toggleClass(element, className, add = true) {
    if (add) {
        element.classList.add(className);
    } else {
        element.classList.remove(className);
    }
}

/**
 * Toggle element visibility
 * @param {HTMLElement} element - Target element
 * @param {boolean} show - Show or hide
 */
function toggleVisibility(element, show = true) {
    element.style.display = show ? '' : 'none';
}

/**
 * Check if element is visible
 * @param {HTMLElement} element - Target element
 * @returns {boolean}
 */
function isVisible(element) {
    return element.offsetParent !== null;
}

/**
 * Get elements by attribute
 * @param {string} attr - Attribute name
 * @param {string} value - Attribute value
 * @returns {NodeList}
 */
function getElementsByAttribute(attr, value) {
    return document.querySelectorAll(`[${attr}="${value}"]`);
}

// ============================================================
// STRING UTILITIES
// ============================================================

/**
 * Escape HTML special characters
 * @param {string} text - Text to escape
 * @returns {string}
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Format currency
 * @param {number} value - Number to format
 * @param {string} currency - Currency code (default: IDR)
 * @returns {string}
 */
function formatCurrency(value, currency = 'IDR') {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: currency,
    }).format(value);
}

/**
 * Format date
 * @param {Date|string} date - Date to format
 * @param {string} format - Format string (default: dd/MM/yyyy)
 * @returns {string}
 */
function formatDate(date, format = 'dd/MM/yyyy') {
    if (typeof date === 'string') {
        date = new Date(date);
    }

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return format
        .replace('dd', day)
        .replace('MM', month)
        .replace('yyyy', year)
        .replace('HH', hours)
        .replace('mm', minutes)
        .replace('ss', seconds);
}

// ============================================================
// ARRAY UTILITIES
// ============================================================

/**
 * Remove duplicates from array
 * @param {Array} array - Input array
 * @returns {Array}
 */
function removeDuplicates(array) {
    return [...new Set(array)];
}

/**
 * Group array by property
 * @param {Array} array - Input array
 * @param {string} key - Property to group by
 * @returns {Object}
 */
function groupBy(array, key) {
    return array.reduce((groups, item) => {
        const group = item[key];
        if (!groups[group]) {
            groups[group] = [];
        }
        groups[group].push(item);
        return groups;
    }, {});
}

// ============================================================
// STORAGE UTILITIES
// ============================================================

/**
 * Get item from localStorage
 * @param {string} key - Storage key
 * @returns {*}
 */
function getStorageItem(key) {
    const item = localStorage.getItem(key);
    try {
        return JSON.parse(item);
    } catch {
        return item;
    }
}

/**
 * Set item in localStorage
 * @param {string} key - Storage key
 * @param {*} value - Value to store
 */
function setStorageItem(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
}

/**
 * Remove item from localStorage
 * @param {string} key - Storage key
 */
function removeStorageItem(key) {
    localStorage.removeItem(key);
}

// ============================================================
// INITIALIZATION
// ============================================================

// Initialize on document ready
document.addEventListener('DOMContentLoaded', function() {
    // Remove alerts after 5 seconds
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        if (!alert.querySelector('button')) {
            setTimeout(() => {
                alert.style.animation = 'fadeOut 0.3s ease-out forwards';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }
    });

    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
    }
});

// Log application version
console.log('%cDataInvest Application', 'color: #2563eb; font-size: 16px; font-weight: bold;');
console.log('%cVersion 2.0 - Modern & Responsive', 'color: #6b7280; font-size: 12px;');
