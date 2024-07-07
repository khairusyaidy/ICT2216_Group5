// inactivity.js
let inactivityTime = function () {
    let time;
    const logoutUrl = 'logout.php'; // Replace with your actual logout URL

    // Reset the timer on any of these events
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
    document.ontouchstart = resetTimer;

    function logout() {
        // Redirect to logout URL after 5 minutes of inactivity
        window.location.href = logoutUrl;
    }

    function resetTimer() {
        clearTimeout(time);
        // Set the timer to 5 minutes (300000 milliseconds)
        time = setTimeout(logout, 300000);
    }
};

// Initialize the inactivity timer
window.onload = inactivityTime;
