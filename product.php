<?php
include 'header.php';
require_once 'db_connect.php';

// Get ID from URL or default to 1
$id = isset($_GET['id']) ? (int) $_GET['id'] : 1;

// Fetch product from database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

// If product not found, redirect to catalog
if (!$product) {
    header("Location: catalog.php");
    exit;
}

// Calculate BGN price
$price_bgn = eurToBgn($product['price_eur']);
?>

<section class="section">
    <div class="container product-layout">

        <!-- Left: Image -->
        <div class="product-image-container">
            <img src="<?php echo $product['image']; ?>" alt="Product" style="border-radius: 12px; width: 100%;">
        </div>

        <!-- Right: Control Panel -->
        <div class="control-panel">
            <h1 class="gradient-text">
                <?php echo htmlspecialchars($product['title']); ?>
            </h1>
            <p>
                <?php echo htmlspecialchars($product['description']); ?>
            </p>

            <hr style="border: 0; border-top: 1px solid var(--card-border); margin: 2rem 0;">

            <!-- Options -->
            <div class="option-group">
                <span class="option-label">Формат:</span>
                <div class="radio-options">
                    <label>
                        <input type="radio" name="format" value="jpg" checked onchange="updateBuyLink()">
                        <span>JPG (High Res)</span>
                    </label>
                    <label>
                        <input type="radio" name="format" value="svg" onchange="updateBuyLink()">
                        <span>SVG (Vector)</span>
                    </label>
                </div>
            </div>

            <div class="option-group">
                <span class="option-label">Лиценз:</span>
                <div class="radio-options">
                    <label>
                        <input type="radio" name="license" value="personal" checked onchange="updatePrice()">
                        <span>Личен</span>
                    </label>
                    <label>
                        <input type="radio" name="license" value="commercial" onchange="updatePrice()">
                        <span>Търговски</span>
                    </label>
                </div>
            </div>

            <!-- Dynamic Price Display -->
            <div class="price-display">
                €<span id="final-price"><?php echo number_format($product['price_eur'], 0); ?></span>
                <span id="price-bgn" style="font-size: 0.5em; opacity: 0.7; display: block;">
                    ≈ <?php echo number_format($price_bgn, 0); ?> лв.
                </span>
            </div>

            <!-- Base price for JS (in EUR) -->
            <input type="hidden" id="base-price" value="<?php echo $product['price_eur']; ?>">
            <input type="hidden" id="product-id" value="<?php echo $id; ?>">
            <input type="hidden" id="eur-to-bgn" value="<?php echo EUR_TO_BGN; ?>">

            <a href="download.php?id=<?php echo $id; ?>&format=jpg" id="buy-link" class="btn-buy">Купи сега</a>
        </div>

    </div>
</section>

<?php include 'footer.php'; ?>