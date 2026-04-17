<?php // Make sure the user is logged in before they can access this page
require "includes/auth.php";

// Connect to the database
require "includes/connect.php";

// Show the admin-style header/navigation
require "includes/header_admin.php";

// Array for validation errors
$errors = [];

// Success message
$success = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and sanitize form values
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));

    // This will store the image path for the database
    $imagePath = null;

    // Validate title
    if ($title === '') {
        $errors[] = "Image title is required.";
    }

    //Add Code Here 
    if($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
        if($_FILES['image']['error'] !== UPLOAD_ERR_OK){
            $errors[] = "There is a problem while uploading your file!";
        }
        else{
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            $detectedType = mime_content_type($_FILES['image']['tmp_name']);
            if(in_array($detectedType, $allowedTypes, true)){
                $errors[] = "Only .jpeg, .jpg, .png and .webp allowed";
            }
            else{
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                $safeFilename = uniqid('product_', true). "." .strtolower($extension);

                $destination = __DIR__ . '/upload/' . $safeFilename;

                if(move_uploaded_file($_FILES['image']['temp_name'], $destination)){
                    //Save relative path
                    $imagePath = 'uploads/' . $safeFilename;
                }
                else{
                    $errors[] = "Image uploaded failed";
                }
            }
        }
    }

    // If there are no errors, insert the product into the database
    if (empty($errors)) {
        $sql = "INSERT INTO images (title, image_path)
                VALUES (:title, :image_path)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':image_path', $imagePath);
        $stmt->execute();

        $success = "Image added successfully!";
    }
}
?>

<main class="container mt-4">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h3>Please fix the following:</h3>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success !== ""): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    <form action="end.php" method="post">
        <h1>Welcome to Gallery of Nobody!</h1>
        <h2>Add Images</h2>
        <!--enctype="multipart/form-data" required for uploads, will not send properly if not included -->
        <form method="post" enctype="multipart/form-data" class="mt-3">

            <label for="image" class="form-label">Image</label>
            <input
                type="file"
                id="image"
                name="image"
                class="form-control mb-4"
                accept=".jpg,.jpeg,.png,.webp"
            >

            <label for="title" class="form-label">Title</label>
            <textarea
                id="title"
                name="title"
                class="form-control mb-3"
                rows="4"
                required
            ></textarea>

            <button type="submit" class="btn btn-primary">Add Image</button>
    </form>
</main>
</html>
<?php require "includes/footer.php"; ?>