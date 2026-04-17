<?php require "includes/header.php";

// READ Logic
$images = $pdo->query("SELECT id, title, image_path FROM images WHERE title = :title OR image_path = :image_path")->fetchAll();

?> <!-- Appreciation page -->
    <main>
        <h1>Thank you for trusting Gallery of Nobody</h1> 
        <p><small>(*Although we don't look that trustworthy)</small></p>
        <h3>This is your image btw</h3>
        <?php echo $images ?>
    </main>
</body>
<?php require "includes/footer.php";?>