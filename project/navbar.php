<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Login - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <div class="container">

        <?php
        // Start the session
        
        // include database connection
        include 'config_folder/database.php';
        ?>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="dashboard.php">Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="create_customer.php">Create
                                Customer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="create_product.php">Create Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="customer_index.php">Customer Index</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="product_index.php">Product Index</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="Order.php">Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="Order_listing.php">Order Listing</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <a button class="btn btn-outline-danger" type="submit" href="User_logout">Logout</button></a>
                    </form>
                </div>
            </div>
        </nav>
</body>

</html>