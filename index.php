<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
 } else {
    $user_id = '';
 }

include 'components/wishlist_cart.php';

// Fetch unique products from the database
$get_unique_products = $conn->prepare("SELECT * FROM products LIMIT 6");
$get_unique_products->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="home-products">
    <h1 class="heading">Latest Products</h1>
    <div class="products-grid">
        <?php
        if ($get_unique_products->rowCount() > 0) {
            while ($unique_product = $get_unique_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="product-card">
            <img src="assets/uploaded_images/<?= htmlspecialchars($unique_product['image_01']); ?>" alt="<?= htmlspecialchars($unique_product['name']); ?>">
            <div class="content">
                <h3><?= htmlspecialchars($unique_product['name']); ?></h3>
                <p><?= htmlspecialchars($unique_product['details']); ?></p>
                <div class="price">R<?= number_format(htmlspecialchars($unique_product['price']), 2); ?></div>
                <form action="" method="post">
                    <input type="hidden" name="pid" value="<?= htmlspecialchars($unique_product['id']); ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($unique_product['name']); ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($unique_product['price']); ?>">
                    <input type="hidden" name="image_01" value="<?= htmlspecialchars($unique_product['image_01']); ?>">
                    <?php if (!empty($unique_product['image_02'])): ?>
                        <input type="hidden" name="image_02" value="<?= htmlspecialchars($unique_product['image_02']); ?>">
                    <?php endif; ?>
                    <?php if (!empty($unique_product['image_03'])): ?>
                        <input type="hidden" name="image_03" value="<?= htmlspecialchars($unique_product['image_03']); ?>">
                    <?php endif; ?>
                    <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                    <a href="product_view_detail.php?pid=<?= htmlspecialchars($unique_product['id']); ?>" class="fas fa-eye"></a>
                    <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                    <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
                </form>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
        ?>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="assets/js/script.js"></script>

</body>
</html>
