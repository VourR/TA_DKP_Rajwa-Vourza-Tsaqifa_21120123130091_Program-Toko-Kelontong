<?php
session_start();

class Item {
    public $name;
    public $price;
    public $image;
    public $quantity; 

    public function __construct($name, $price, $image) {
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
        $this->quantity = 0; 
    }
}

$items = [
    new Item("Indomie Mi Goreng", 3000, "images/indomie_goreng.jpg"),
    new Item("Indomie Mi Goreng Rasa Rendang", 3500, "images/indomie_rendang.jpg"),
    new Item("Indomie Mi Instan Mi Keriting Goreng Spesial", 3000, "images/indomie_goreng_spesial.jpg"),
    new Item("Indomie Mi Goreng Rasa Ayam Pop", 3000, "images/indomie_ayam_pop.jpg"),
    new Item("Indomie Hype Abis Mi Goreng Rasa Ayam Geprek", 3500, "images/indomie_ayam_geprek.jpg"),
    new Item("Indomie Mi Keriting Rasa Ayam Panggang", 3000, "images/indomie_ayam_panggang.jpg"),
    new Item("Indomie Mi Goreng Satay Flavour", 3000, "images/indomie_satay.jpg"),
    new Item("Indomie Mi Goreng Hot & Spicy", 3000, "images/indomie_hot_spicy.jpg"),
    new Item("Indomie Mi Goreng Barbecue Flavour", 3000, "images/indomie_barbecue.jpg"),
    new Item("Indomie Curry Chicken Soup Flavour", 3000, "images/indomie_curry_chicken.jpg"),
    new Item("Indomie rasa ayam spesial", 3000, "images/indomie_ayam_spesial.jpg"),
    new Item("Indomie rasa soto mie", 3000, "images/indomie_soto_mie.jpg"),
    new Item("Indomie rasa baso sapi", 3000, "images/indomie_baso_sapi.jpg"),
    new Item("Indomie rasa kaldu udang", 3000, "images/indomie_kaldu_udang.jpg"),
    new Item("Indomie rasa soto spesial", 3000, "images/indomie_soto_spesial.jpg"),
    new Item("Indomie rasa kari ayam dengan bawang goreng", 3000, "images/indomie_kari_ayam.jpg"),
    new Item("Indomie rasa kari ayam", 3000, "images/indomie_kari_ayam.jpg"),
    new Item("Indomie rasa kaldu ayam", 3000, "images/indomie_kaldu_ayam.jpg")
];



if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Inisialisasi $_SESSION['cart'] sebagai array kosong jika belum ada atau bukan array
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item_name = $_POST['item_name'];
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item->name === $item_name) {
            $cart_item->quantity += 1;
            $found = true;
            break;
        }
    }
    if (!$found) {
        foreach ($items as $item) {
            if ($item->name === $item_name) {
                $item->quantity = 1;
                $_SESSION['cart'][] = $item;
                break;
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset_cart'])) {
        $_SESSION['cart'] = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nama Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white p-3 mb-4">
        <div class="container d-flex justify-content-between">
            <h1 class="mb-0">Nama Toko</h1>
            <div>
                <button id="cartButton" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#cartModal">Cart</button>
                <button id="logoutButton" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <?php foreach ($items as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo $item->image; ?>" class="card-img-top" alt="<?php echo $item->name; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $item->name; ?></h5>
                            <form method="POST">
                                <input type="hidden" name="item_name" value="<?php echo $item->name; ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                                <button type="button" class="btn btn-outline-success" disabled>Price: Rp. <?php echo $item->price; ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (empty($_SESSION['cart'])): ?>
                        <p>Your cart is empty.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php $total = 0; ?>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <li class="list-group-item">
                                    <?php echo $item->name; ?> - Rp. <?php echo $item->price; ?> - <?php echo $item->quantity; ?> item(s)
                                </li>
                                <?php $total += ($item->price * $item->quantity); ?>
                            <?php endforeach; ?>
                        </ul>
                        <p class="mt-3">Total: Rp. <?php echo $total; ?></p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <form method="POST">
                        <button type="submit" name="reset_cart" class="btn btn-secondary">Reset Cart</button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutModal">Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Item berhasil dipesan! Apakah Anda ingin memesan lagi?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <a href="login.php" class="btn btn-danger">Ya</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>

