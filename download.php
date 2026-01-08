<?php
// Get product ID and format from URL
$id = $_GET['id'] ?? '1';
$format = $_GET['format'] ?? 'jpg';

// Product database
$products = [
    '1' => ['title' => 'Neon Synapse', 'img' => 'assets/prod1.png'],
    '2' => ['title' => 'Geometric Gold', 'img' => 'assets/prod2.png'],
    '3' => ['title' => 'Liquid Hologram', 'img' => 'assets/prod3.png'],
];

$product = $products[$id] ?? $products['1'];

// Determine file extension based on format selection
$extension = ($format === 'svg') ? 'svg' : 'jpg';
$formatLabel = ($format === 'svg') ? 'SVG (Vector)' : 'JPG (High Res)';

include 'header.php';
?>

<section class="section"
    style="min-height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center;">
    <div class="container">
        <div class="control-panel" style="max-width: 600px; margin: 0 auto;">

            <h1 class="gradient-text" style="font-size: 3rem; margin-bottom: 2rem;">Благодарим ви!</h1>

            <p style="font-size: 1.2rem; margin-bottom: 0.5rem;">
                Вашата поръчка за <strong><?php echo $product['title']; ?></strong> беше успешна.
            </p>
            <p style="font-size: 1rem; margin-bottom: 2rem; opacity: 0.7;">
                Формат: <strong><?php echo $formatLabel; ?></strong>
            </p>

            <a href="<?php echo $product['img']; ?>"
                download="<?php echo $product['title']; ?>.<?php echo $extension; ?>" class="btn-buy"
                style="max-width: 300px; margin: 0 auto;">
                Изтегли <?php echo $product['title']; ?>.<?php echo $extension; ?>
            </a>

            <p style="margin-top: 2rem; font-size: 0.9rem; opacity: 0.6;">
                На имейла ви е изпратена и фактура.
            </p>

            <a href="catalog.php" style="display: inline-block; margin-top: 1rem; text-decoration: underline;">
                Обратно към магазина
            </a>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>