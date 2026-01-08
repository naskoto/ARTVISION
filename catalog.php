<?php
// Include database connection FIRST to set UTF-8 encoding before any output
require_once 'db_connect.php';

// Get filter and sort parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Validate sort column
$allowed_sort = ['id', 'price_eur', 'title'];
if (!in_array($sort, $allowed_sort)) {
    $sort = 'id';
}

// Validate order
$order = strtolower($order) === 'desc' ? 'DESC' : 'ASC';

// Build query
$sql = "SELECT * FROM products";
$params = [];

if (!empty($category)) {
    $sql .= " WHERE category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY $sort $order";

// Execute query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get all categories for filter dropdown
$cat_stmt = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category");
$categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<?php include 'header.php'; ?>

<section class="section">
    <div class="container">
        <h1>Нашата Колекция</h1>
        <p style="margin-bottom: 2rem; max-width: 600px;">
            Разгледайте курираната ни селекция от дигитални графики.
        </p>

        <!-- Filter & Sort Controls -->
        <div class="filter-toolbar">
            <form method="GET" action="catalog.php" class="filter-form">

                <!-- Category Filter -->
                <div class="filter-group">
                    <label for="category">Категория:</label>
                    <select name="category" id="category" class="filter-select" onchange="this.form.submit()">
                        <option value="">Всички</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sort -->
                <div class="filter-group">
                    <label for="sort">Сортирай по:</label>
                    <select name="sort" id="sort" class="filter-select" onchange="this.form.submit()">
                        <option value="id" <?php echo $sort === 'id' ? 'selected' : ''; ?>>Нови</option>
                        <option value="price_eur" <?php echo $sort === 'price_eur' ? 'selected' : ''; ?>>Цена</option>
                        <option value="title" <?php echo $sort === 'title' ? 'selected' : ''; ?>>Име</option>
                    </select>
                </div>

                <!-- Order -->
                <div class="filter-group">
                    <label for="order">Ред:</label>
                    <select name="order" id="order" class="filter-select" onchange="this.form.submit()">
                        <option value="asc" <?php echo $order === 'ASC' ? 'selected' : ''; ?>>Възходящ ↑</option>
                        <option value="desc" <?php echo $order === 'DESC' ? 'selected' : ''; ?>>Низходящ ↓</option>
                    </select>
                </div>

                <!-- Results Count -->
                <div class="filter-results">
                    <span class="results-count"><?php echo count($products); ?> продукта</span>
                </div>

            </form>
        </div>

        <div class="gallery-grid">
            <?php foreach ($products as $prod): ?>
                <a href="product.php?id=<?php echo $prod['id']; ?>" class="gallery-item" style="display: block;">
                    <img src="<?php echo $prod['image']; ?>" alt="<?php echo htmlspecialchars($prod['title']); ?>">
                    <div class="gallery-info">
                        <?php if (!empty($prod['category'])): ?>
                            <span class="category-badge"><?php echo htmlspecialchars($prod['category']); ?></span>
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($prod['title']); ?></h3>
                        <p style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 0.5rem;">
                            <?php echo htmlspecialchars(mb_substr($prod['description'], 0, 40)) . '...'; ?>
                        </p>
                        <span class="price"><?php echo formatPrice($prod['price_eur']); ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="no-results">
                <p>Няма намерени продукти в тази категория.</p>
                <a href="catalog.php" class="btn-hero secondary">Покажи всички</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>