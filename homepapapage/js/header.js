// Get references to the text element and the toggle button
const userDrop = document.getElementById('user-drop');
const toggleUser = document.getElementById('toggleUser');
const notifDrop = document.getElementById('notif-drop');
const toggleNotif = document.getElementById('toggleNotif');

// Add a click event listener to the toggle button
toggleUser.addEventListener('click', function() {
    // Toggle the visibility of the text element
    if (userDrop.style.display == 'none') {
        userDrop.style.display = 'block'; // or 'inline', 'inline-block', etc. depending on the desired display type
        notifDrop.style.display = 'none';
    } else {
        userDrop.style.display = 'none';
    }
});

// Add a click event listener to the toggle button
toggleNotif.addEventListener('click', function() {
    // Toggle the visibility of the text element
    if (notifDrop.style.display == 'none') {
        notifDrop.style.display = 'block'; // or 'inline', 'inline-block', etc. depending on the desired display type
        userDrop.style.display = 'none';
    } else {
        notifDrop.style.display = 'none';
    }
});