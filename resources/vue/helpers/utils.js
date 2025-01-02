export function getURLAPI() {
    return window.location.protocol + '//' + window.location.host + '/api';
}

export function showToast(title = null, message = null, color = null, icon = null, position = 'top right', displayTime = 5000) {
    let options = {
        title           : title,
        message         : message,
        class           : color,
        showIcon        : icon,
        showProgress    : 'bottom',
        position        : position,
        displayTime     : displayTime,
    };
    $.toast(options);
}