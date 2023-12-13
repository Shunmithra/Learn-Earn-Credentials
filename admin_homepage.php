<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

    <!-- Add your custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Add any additional styles specific to this page here */
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #333;
            padding-top: 20px;
        }

        .sidenav a {
            display: block;
            padding: 10px;
            width: 100%;
            background-color: transparent;
            border: none;
            color: white;
            text-align: left;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none; /* Added this line for anchor links */
        }

        .sidenav a:hover {
            background-color: #555;
        }

        .content {
            padding: 10px;
            display: none;
            overflow: hidden;
            background-color: #f9f9f9;
            max-height: 0;
            transition: max-height 0.2s ease-out;
        }

        .content a {
            display: block;
            padding: 5px;
            text-decoration: none;
            color: #333;
        }

        .content a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="sidenav">
        <a href="admin_profile.php">Admin Profile</a>
        
        <a href="all_profile.php">All User's Profile</a>
        
        <a href="uploaded_pdfs.php">Uploaded PDFs with Author Profiles</a>
        
        <a href="feedback.php">Feedback</a>
    </div>

    <div class="main-content">
        <!-- Your main content here -->
    </div>
</body>
</html>
