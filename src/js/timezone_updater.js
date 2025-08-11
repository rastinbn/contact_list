document.addEventListener('DOMContentLoaded', function() {
    const latitude = 35.6892;
    const longitude = 51.3890;

    fetch('/contact_list/modules/get_timezone.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `latitude=${latitude}&longitude=${longitude}`,
    })
        .then(response => response.json())
        .then(data => {
            console.log('Received data from backend:', data);

            if (data.error) {
                console.error('Error from backend:', data.error);
                document.getElementById('timezone-display').innerText = `Error: ${data.error}`;
                document.getElementById('current-time-display').innerText = '';
                return;
            }

            if (data.timezone) {
                document.getElementById('timezone-display').innerText += ` ${data.timezone}`;
            } else {
                document.getElementById('timezone-display').innerText = '';
            }

            if (data.hour !== undefined && data.minute !== undefined) {
                document.getElementById('current-time-display').innerText += ` ${data.hour}:${data.minute}`;
            } else {
                document.getElementById('current-time-display').innerText = '';
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById('timezone-display').innerText = `Fetch error: ${error.message}`;
            document.getElementById('current-time-display').innerText = '';
        });
});
