<?php include '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agroshop Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; color: #333; }
        .header { background: #28a745; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header .logo { font-size: 24px; font-weight: bold; }
        .nav-links a { color: white; text-decoration: none; margin-left: 20px; transition: color 0.3s; }
        .nav-links a:hover { color: #14524A; }
        .sidebar { width: 250px; background: #fff; height: 100vh; position: fixed; padding: 20px; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar ul { list-style: none; }
        .sidebar ul li { margin-bottom: 15px; }
        .sidebar ul li a { color: #28a745; text-decoration: none; font-weight: 500; transition: color 0.3s; }
        .sidebar ul li a:hover { color: #218838; }
        .main-content { margin-left: 270px; padding: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .table th { background: #28a745; color: white; }
        .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        .btn-primary { background: #28a745; color: white; }
        .btn-primary:hover { background: #218838; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: #fff; padding: 20px; border-radius: 8px; width: 400px; position: relative; }
        .close { position: absolute; top: 10px; right: 10px; font-size: 20px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Agroshop Admin</div>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="sidebar">
        <ul>
            <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="farmers.php"><i class="fas fa-users"></i> Farmers</a></li>
            <li><a href="products.php"><i class="fas fa-box"></i> Products</a></li>
            <li><a href="contactus.php"><i class="fas fa-envelope"></i> Contact Us</a></li>
        </ul>
    </div>
    <div class="main-content">