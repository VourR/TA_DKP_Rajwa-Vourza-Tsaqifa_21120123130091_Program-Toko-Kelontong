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
    new Item("Beras Pandan Wangi 5kg", 60000, "images/beras_pandan_wangi.jpg"),
    new Item("Minyak Goreng Bimoli 1L", 14000, "images/minyak_goreng_bimoli.jpg"),
    new Item("Gula Pasir Gulaku 1kg", 12000, "images/gula_gulaku.jpg"),
    new Item("Telur Ayam Ras 1kg", 22000, "images/telur_ayam.png"),
    new Item("Susu UHT Ultra Milk 1L", 15000, "images/susu_ultra.jpg"),
    new Item("Roti Tawar Sari Roti", 12000, "images/roti_sari_roti.jpg"),
    new Item("Coca Cola 1.5L", 15000, "images/coca_cola.jpg"),
    new Item("Air Mineral Aqua 600ml", 3000, "images/aqua.png"),
    new Item("Lays Keripik Kentang 70g", 10000, "images/lays.jpg"),
    new Item("Sabun Mandi Lifebuoy 90g", 5000, "images/sabun_lifebuoy.jpg"),
    new Item("Shampoo Pantene 180ml", 20000, "images/shampoo_pantene.jpg"),
    new Item("Pasta Gigi Pepsodent 190g", 12000, "images/pasta_gigi_pepsodent.jpg"),
    new Item("Tisu Wajah Paseo 150s", 10000, "images/tisu_paseo.png"),
    new Item("Tisu Gulung Paseo 10s", 20000, "images/tisu_gulung_paseo.jpg"),
    new Item("Chitato Keripik Kentang 68g", 9000, "images/chitato.jpg"),
    new Item("Bumbu Masak Indofood Racik Nasi Goreng 20g", 3000, "images/bumbu_racik.jpg"),
    new Item("Indomie Mi Goreng", 3000, "images/indomie_goreng.jpg"),
    new Item("Detergen Rinso 1kg", 18000, "images/detergen_rinso.jpg"),
    new Item("Kopi Instan Nescafe 3in1 30g", 2000, "images/nescafe.jpg"),
    new Item("Teh Celup Sariwangi 25s", 7000, "images/teh_sariwangi.jpg")
    
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
                <button id="cartButton" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#cartModal">Keranjang</button>
                <button id="logoutButton" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <?php foreach ($items as $item): ?>
                <div class="col-md-4 mb-4 d-flex">
                    <div class="card h-100 w-100">
                        <div class="card-img-container" style="height: 400px; overflow: hidden;">
                            <img src="<?php echo $item->image; ?>" class="card-img-top img-fluid" style="height: 100%; object-fit: cover;" alt="<?php echo $item->name; ?>">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $item->name; ?></h5>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <form method="POST" class="me-2">
                                    <input type="hidden" name="item_name" value="<?php echo $item->name; ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary">Tambah ke Keranjang</button>
                                </form>
                                <button type="button" class="btn btn-outline-success" disabled>Rp. <?php echo $item->price; ?></button>
                            </div>
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
                    <h5 class="modal-title" id="cartModalLabel">Keranjang Kamu</h5>
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
                        <button type="button" name="checkout" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutModal">Checkout</button>
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
                    <form method="POST">
                        <button type="submit" name="reset_cart" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                        <button type="submit" name="reset_cart" class="btn btn-primary" data-bs-dismiss="modal">Ya</button>
                <form>
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



