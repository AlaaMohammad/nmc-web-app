<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F7F7F7;
        }
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 30px;
        }
        .card {
            width: 18rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }
        .card img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #9A0906;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #761507;
        }
    </style>
</head>
<body>
<div class="container text-center">
    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
    <h1 class="mb-5">Welcome to the Dashboard</h1>
    <div class="row justify-content-center gap-3">
        <!-- Card 1 -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Admin Dashboard</h5>
                <p class="card-text">Access and manage all admin functionalities.</p>
                <a href="{{ route('admin.login') }}" class="btn btn-primary">Login as Admin</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
