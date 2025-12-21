function appendAlert(message, type) {
    const alertPlaceholder = document.getElementById('liveAlert');
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible" role="alert">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('')
    alertPlaceholder.append(wrapper)
}

function updateStatus() {
    fetch('/functions/status.php')
        .then(response => response.json())
        .then(data => {
            const statusEl = document.getElementById('server-status');
            if(data.online) {
                statusEl.innerHTML = '<span class="badge text-bg-success">' + data.status + '</span>';
            } else {
                statusEl.innerHTML = '<span class="badge text-bg-danger">' + data.status + '</span>';
            }
        }).catch(err => appendAlert(err, 'danger'));
}

updateStatus();