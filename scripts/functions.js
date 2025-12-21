function startServer() {
    fetch('/functions/start.php')
        .then(response => response.text())
        .then(html => {
             console.log("Response: " + html);
            if(html == "") {
                appendAlert('Command executed without any errors', 'success');
            } else {
                appendAlert('Error: ' + html, 'danger');
            }           
        }).catch(err => appendAlert(err, 'danger'));
}


function stopServer() {
    fetch('/functions/stop.php')
       .then(response => response.text())
        .then(html => {
             console.log("Response: " + html);
            if(html == "") {
                appendAlert('Command executed without any errors', 'success');
            } else {
                appendAlert('Error: ' + html, 'danger');
            }           
        }).catch(err => appendAlert(err, 'danger'));
}

function reStartServer() {
    fetch('/functions/restart.php')
        .then(response => response.text())
        .then(html => {
             console.log("Response: " + html);
            if(html == "") {
                appendAlert('Command executed without any errors', 'success');
            } else {
                appendAlert('Error: ' + html, 'danger');
            }           
        }).catch(err => appendAlert(err, 'danger'));
}
