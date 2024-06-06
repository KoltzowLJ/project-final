<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
   exit();
}

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
   exit();
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = filter_var($_POST['qty'], FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'Cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WJVLZYDW1W"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-WJVLZYDW1W');
    </script>

   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>
   
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">

   <h3 class="heading">Shopping Cart</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="cart_id" value="<?= htmlspecialchars($fetch_cart['id']); ?>">
      <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_cart['pid']); ?>" class="fas fa-eye"></a>
      <img src="assets/uploaded_images/<?= htmlspecialchars($fetch_cart['image']); ?>" alt="<?= htmlspecialchars($fetch_cart['name']); ?>">
      <div class="name"><?= htmlspecialchars($fetch_cart['name']); ?></div>
      <div class="flex">
         <div class="price">R<?= htmlspecialchars($fetch_cart['price']); ?></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= htmlspecialchars($fetch_cart['quantity']); ?>">
         <button type="submit" class="fas fa-edit" name="update_qty"></button>
      </div>
      <div class="sub-total"> Sub Total: <span>R<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></span> </div>
      <input type="submit" value="Delete Item" onclick="return confirm('Delete this from cart?');" class="delete-btn" name="delete">
   </form>
   <?php
   $grand_total += $sub_total;
      }
   } else {
      echo '<p class="empty">Your cart is empty</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>Grand Total: <span>R<?= htmlspecialchars($grand_total); ?></span></p>
      <a href="shop.php" class="option-btn">Continue Shopping</a>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All Items</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="assets/js/script.js"></script>

</body>
</html>
