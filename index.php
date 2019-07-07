<?php
// starten van de session
session_start();
// binnenhalen van db.php zodat de database connect
include('db.php');
$status="";
if (isset($_POST['code']) && $_POST['code']!=""){
    $code = $_POST['code'];
    $result = mysqli_query(
        $con,
        "SELECT * FROM `products` WHERE `code`='$code'"
    );
    // declareren van variabels
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $code = $row['code'];
    $price = $row['price'];
    $image = $row['image'];

    $cartArray = array(
        $code=>array(
            'name'=>$name,
            'code'=>$code,
            'price'=>$price,
            'quantity'=>1,
            'image'=>$image)
    );
//if else statement : als shoppingcart leeg is en er word geklikt op een product voeg product toe en en verander $status naar Added product
    if(empty($_SESSION["shopping_cart"])) {
        $_SESSION["shopping_cart"] = $cartArray;
        $status = "<div class='box'>Product is added to your cart!</div>";
    }else{
        $array_keys = array_keys($_SESSION["shopping_cart"]);
// als product al in de shoppingcart zit verander $status naar product is already added
        if(in_array($code,$array_keys)) {
            $status = "<div class='box' style='color:red;'>
	Product is already added to your cart!</div>";
        } else {
            $_SESSION["shopping_cart"] = array_merge(
                $_SESSION["shopping_cart"],
                $cartArray
            );
            $status = "<div class='box'>Product is added to your cart!</div>";
        }

    }
}
?>
<?php
// Het uitrekenen van het totaal bedrag
if(!empty($_SESSION["shopping_cart"])) {
    $cart_count = count(array_keys($_SESSION["shopping_cart"]));
    ?>
    <div class="cart_div">
        <a href="cart.php"><img src="cart-icon.png" /> Cart<span>
<?php echo $cart_count; ?></span></a>
    </div>
    <?php
}
?>
<?php
$result = mysqli_query($con,"SELECT * FROM `products`");
while($row = mysqli_fetch_assoc($result)){
    echo "<div class='product_wrapper'>
    <form method='post' action=''>
    <input type='hidden' name='code' value=".$row['code']." />
    <div class='image'><img src='".$row['image']."' /></div>
    <div class='name'>".$row['name']."</div>
    <div class='price'>$".$row['price']."</div>
    <button type='submit' class='buy'>Buy Now</button>
    </form>
    </div>";
}
mysqli_close($con);
?>

<div style="clear:both;"></div>

<div class="message_box" style="margin:10px 0px;">
    <?php echo $status; ?>
</div>
