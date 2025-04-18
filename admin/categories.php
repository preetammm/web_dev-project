<?php
require_once '../config/db_config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Require admin
require_admin();

// Handle category deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $category_id = clean_input($_GET['id']);

    // Check if category exists
    $sql = "SELECT id FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['error'] = "Category not found";
        redirect("categories.php");
    }

    // Check if category is in use
    $sql = "SELECT COUNT(*) as count FROM grievances WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        $_SESSION['error'] = "Cannot delete category because it is being used by " . $row['count'] . " grievance(s)";
        redirect("categories.php");
    }

    // Delete category
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Category deleted successfully";
    } else {
        $_SESSION['error'] = "Failed to delete category";
    }

    redirect("categories.php");
}

// Handle category addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = clean_input($_POST['name']);

    if (empty($name)) {
        $_SESSION['error'] = "Category name cannot be empty";
        redirect("categories.php");
    }

    // Check if category already exists
    $sql = "SELECT id FROM categories WHERE name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Category already exists";
        redirect("categories.php");
    }

    // Add category
    $sql = "INSERT INTO categories (name) VALUES (?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Category added successfully";
    } else {
        $_SESSION['error'] = "Failed to add category";
    }

    redirect("categories.php");
}

// Handle category edit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $category_id = clean_input($_POST['category_id']);
    $name = clean_input($_POST['name']);

    if (empty($name)) {
        $_SESSION['error'] = "Category name cannot be empty";
        redirect("categories.php");
    }

    // Check if category exists
    $sql = "SELECT id FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['error'] = "Category not found";
        redirect("categories.php");
    }

    // Check if name already exists for another category
    $sql = "SELECT id FROM categories WHERE name = ? AND id != ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $name, $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Category name already exists";
        redirect("categories.php");
    }

    // Update category
    $sql = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $name, $category_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Category updated successfully";
    } else {
        $_SESSION['error'] = "Failed to update category";
    }

    redirect("categories.php");
}

// Get all categories with usage count
$sql = "SELECT c.id, c.name, COUNT(g.id) as grievance_count
        FROM categories c
        LEFT JOIN grievances g ON c.id = g.category_id
        GROUP BY c.id
        ORDER BY c.name ASC";
$result = mysqli_query($conn, $sql);

include 'admin_header.php';
?>

<div class="admin-page-header">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1><i class="fas fa-tags me-2"></i>Category Management</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="dashboard.php" class="btn btn-glass"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4 admin-glass">
            <div class="card-header bg-transparent text-white">
                <h4 class="mb-0">All Categories</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Grievances</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo $row['grievance_count']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary edit-category"
                                                data-id="<?php echo $row['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($row['name']); ?>">
                                                Edit
                                            </button>
                                            <?php if ($row['grievance_count'] == 0): ?>
                                                <a href="categories.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No categories found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4 admin-glass">
            <div class="card-header bg-transparent text-white">
                <h4 class="mb-0">Add New Category</h4>
            </div>
            <div class="card-body">
                <form action="categories.php" method="post">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>

        <div class="card mb-4 admin-glass" id="edit-category-card" style="display: none;">
            <div class="card-header bg-transparent text-white">
                <h4 class="mb-0">Edit Category</h4>
            </div>
            <div class="card-body">
                <form action="categories.php" method="post">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="category_id" id="edit-category-id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <button type="button" class="btn btn-secondary" id="cancel-edit">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Edit category functionality
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-category');
    const editCard = document.getElementById('edit-category-card');
    const editCategoryId = document.getElementById('edit-category-id');
    const editName = document.getElementById('edit-name');
    const cancelButton = document.getElementById('cancel-edit');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            editCategoryId.value = id;
            editName.value = name;
            editCard.style.display = 'block';

            // Scroll to edit form
            editCard.scrollIntoView({ behavior: 'smooth' });
        });
    });

    cancelButton.addEventListener('click', function() {
        editCard.style.display = 'none';
    });
});
</script>

<?php include 'admin_footer.php'; ?>
