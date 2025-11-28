$(document).ready(function() {
    const API_BASE = 'PHP/';

    // Check if user is logged in
    const token = localStorage.getItem('jwt_token');
    if (token) {
        showProfile();
        fetchProfile();
    }

    // Register
    $('#register-btn').click(function() {
        const username = $('#reg-username').val();
        const email = $('#reg-email').val();
        const password = $('#reg-password').val();

        if (!username || !email || !password) {
            $('#register-message').html('<div class="alert alert-danger">All fields are required.</div>');
            return;
        }

        $.ajax({
            url: API_BASE + 'register.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ username, email, password }),
            success: function(response) {
                $('#register-message').html('<div class="alert alert-success">' + response.message + '</div>');
                $('#register-form')[0].reset();
            },
            error: function(xhr) {
                const error = JSON.parse(xhr.responseText);
                $('#register-message').html('<div class="alert alert-danger">' + error.message + '</div>');
            }
        });
    });

    // Login
    $('#login-btn').click(function() {
        const username = $('#login-username').val();
        const password = $('#login-password').val();

        if (!username || !password) {
            $('#login-message').html('<div class="alert alert-danger">All fields are required.</div>');
            return;
        }

        $.ajax({
            url: API_BASE + 'login.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ username, password }),
            success: function(response) {
                localStorage.setItem('jwt_token', response.token);
                showProfile();
                fetchProfile();
                $('#login-form')[0].reset();
            },
            error: function(xhr) {
                const error = JSON.parse(xhr.responseText);
                $('#login-message').html('<div class="alert alert-danger">' + error.message + '</div>');
            }
        });
    });

    // Fetch Profile
    function fetchProfile() {
        const token = localStorage.getItem('jwt_token');
        $.ajax({
            url: API_BASE + 'profile.php',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            success: function(response) {
                $('#profile-username').val(response.username);
                $('#profile-email').val(response.email);
                $('#profile-bio').val(response.bio);
            },
            error: function(xhr) {
                const error = JSON.parse(xhr.responseText);
                $('#profile-message').html('<div class="alert alert-danger">' + error.message + '</div>');
            }
        });
    }

    // Update Profile
    $('#update-profile-btn').click(function() {
        const email = $('#profile-email').val();
        const bio = $('#profile-bio').val();

        if (!email) {
            $('#profile-message').html('<div class="alert alert-danger">Email is required.</div>');
            return;
        }

        const token = localStorage.getItem('jwt_token');
        $.ajax({
            url: API_BASE + 'profile.php',
            type: 'POST',
            contentType: 'application/json',
            headers: { 'Authorization': 'Bearer ' + token },
            data: JSON.stringify({ email, bio }),
            success: function(response) {
                $('#profile-message').html('<div class="alert alert-success">' + response.message + '</div>');
            },
            error: function(xhr) {
                const error = JSON.parse(xhr.responseText);
                $('#profile-message').html('<div class="alert alert-danger">' + error.message + '</div>');
            }
        });
    });

    // Logout
    $('#logout-btn').click(function() {
        const token = localStorage.getItem('jwt_token');
        $.ajax({
            url: API_BASE + 'logout.php',
            type: 'POST',
            contentType: 'application/json',
            headers: { 'Authorization': 'Bearer ' + token },
            success: function(response) {
                localStorage.removeItem('jwt_token');
                showLogin();
            },
            error: function(xhr) {
                const error = JSON.parse(xhr.responseText);
                alert('Logout failed: ' + error.message);
            }
        });
    });

    function showProfile() {
        $('#register-card, #login-card').hide();
        $('#profile-card').show();
    }

    function showLogin() {
        $('#profile-card').hide();
        $('#register-card, #login-card').show();
        $('#register-message, #login-message, #profile-message').empty();
    }
});