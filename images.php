<?php
require "includes/auth.php";
require "includes/connect.php";
require "includes/header.php";

// DELETE Logic
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: display.php?msg=Deleted");
    exit;
}

?>

<h2>Image Management (A feature for admin only)</h2>
<table>
    <tr>
        <th>Title</th>
        <th>Path</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($images as $i): ?>
    <tr>
        <td><?= htmlspecialchars($i['title']) ?></td>
        <td><?= htmlspecialchars($i['image_path']) ?></td>
        <td>
            <a href="index.php?edit_id=<?= $r['id'] ?>">Edit</a> | 
            <a href="display.php?delete_id=<?= $r['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require "includes/footer.php"; ?>