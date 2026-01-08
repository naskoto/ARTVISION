<?php
// Form Processing Logic
$messageSent = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($message)) {
        // Simulate sending/saving
        // file_put_contents('messages.txt', "$name ($email): $message\n", FILE_APPEND);
        $messageSent = true;
    } else {
        $error = "Моля попълнете всички полета.";
    }
}
include 'header.php';
?>

<section class="section">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        <h1>Свържете се с нас</h1>
        <p>Имате въпроси или искате да поръчате индивидуална графика? Попълнете формата.</p>

        <?php if ($messageSent): ?>
            <div style="
                background-color: #d4edda; 
                color: #155724; 
                padding: 1rem; 
                margin: 2rem 0; 
                border: 1px solid #c3e6cb;
                border-radius: 4px;
            ">
                Благодарим Ви! Вашето съобщение беше изпратено успешно.
            </div>
        <?php elseif ($error): ?>
            <div style="
                background-color: #f8d7da; 
                color: #721c24; 
                padding: 1rem; 
                margin: 2rem 0; 
                border: 1px solid #f5c6cb;
                border-radius: 4px;
            ">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="contacts.php" style="margin-top: 2rem;">
            <div style="margin-bottom: 2rem;">
                <label for="name" class="form-label">Име</label>
                <input type="text" id="name" name="name" class="form-input" required>
            </div>

            <div style="margin-bottom: 2rem;">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>

            <div style="margin-bottom: 2rem;">
                <label for="message" class="form-label">Съобщение</label>
                <textarea id="message" name="message" rows="5" class="form-textarea" required></textarea>
            </div>

            <button type="submit" style="
                padding: 1rem 3rem;
                background-color: var(--text-color);
                color: var(--bg-color);
                border: none;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                cursor: pointer;
                transition: opacity 0.3s;
            ">Изпрати</button>
        </form>

        <div style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
            <h3>Контакти</h3>
            <p><strong>Адрес:</strong> бул. Витоша 91, София</p>
            <p><strong>Телефон:</strong> +359 888 123 456</p>
            <p><strong>Email:</strong> info@artvision.bg</p>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>