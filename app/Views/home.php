<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyShop-ecommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
     <link rel="stylesheet" href="<?= base_url('css/homepage.css') ?>">
  
</head>

<body>
    
   <nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container-fluid justify-content-between align-items-center">

     <button class="navbar-toggler d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list fs-3"></i>
    </button>
    <a class="navbar-brand fw-bold" href="#">MyShop</a>

    <ul class="navbar-nav flex-row gap-3 d-none d-lg-flex">
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Men</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Women</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Kids</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Home</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Beauty</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">GenZ</a></li>
    </ul>
      <!-- only for desktop-->
    <!-- Search Bar -->
    <form class="search-box d-none d-lg-block mx-4 w-50">
      <div class="input-group rounded-pill bg-light">
        <span class="input-group-text bg-transparent border-0 ps-3">
          <i class="bi bi-search text-muted"></i>
        </span>
        <input type="search" class="form-control border-0 bg-transparent" placeholder="Search for products">
      </div>
    </form>

    <!-- Icons -->
    <ul class="navbar-nav flex-row gap-3 text-center">
      <li class="nav-item">
        <a class="nav-link d-flex flex-column align-items-center" href="#">
          <i class="bi bi-person fs-5"></i>
          <span class="small">Profile</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex flex-column align-items-center" href="#">
          <i class="bi bi-heart fs-5"></i>
          <span class="small">Wishlist</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link d-flex flex-column align-items-center" href="#">
          <i class="bi bi-bag fs-5"></i>
          <span class="small">Bag</span>
        </a>
      </li>
    </ul>
  </div>
  <!-- for mobile  -->
   <div class="collapse d-lg-none bg-white px-3 pt-2" id="mobileNav">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Men</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Women</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Kids</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Home</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">Beauty</a></li>
      <li class="nav-item"><a class="nav-link fw-semibold text-dark" href="#">GenZ</a></li>
    </ul>
  </div>
</nav>


    <div class="container mt-4">
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?= base_url('images/' . $product['image']) ?>" class="card-img-top product-img" alt="<?= $product['name'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $product['name'] ?></h5>
                            <p class="card-text">₹<?= number_format($product['price'], 2) ?></p>
                            <a href="#" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div> <?php endforeach; ?>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>