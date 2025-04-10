<?php
session_start();
error_reporting(E_ALL); // Changed from 0 to see all errors during development
require_once __DIR__ . '/../../config.php';

if (empty($_SESSION['alogin'])) {
    header('location:../auth/login.php');
    exit();
}

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$name = $category = $price = $currentImage = '';
$errors = [];

if (isset($_POST['update'])) {
    $name = trim($_POST['name'] ?? '');
    $category = intval($_POST['category'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $currentImage = trim($_POST['currentimage'] ?? '');
    $image = $currentImage;

    if (empty($name)) {
        $errors[] = "Product name is required";
    }
    if ($category <= 0) {
        $errors[] = "Invalid category selected";
    }
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }

    if (empty($errors) && !empty($_FILES["productimg"]["name"])) {
        $newImage = $_FILES["productimg"]["name"];
        $extension = strtolower(pathinfo($newImage, PATHINFO_EXTENSION));
        $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = "Invalid format. Only jpg/jpeg/png/gif formats allowed";
        } elseif ($_FILES["productimg"]["size"] > $maxFileSize) {
            $errors[] = "File size must be less than 2MB";
        } else {
            $newImageName = md5($newImage . time()) . "." . $extension;
            $uploadPath = "../productImg/" . $newImageName;

            if (move_uploaded_file($_FILES["productimg"]["tmp_name"], $uploadPath)) {
                $image = $newImageName;

                if (!empty($currentImage) && file_exists("../productImg/" . $currentImage)) {
                    unlink("../productImg/" . $currentImage);
                }
            } else {
                $errors[] = "Failed to upload image";
            }
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE products SET name=:name, catid=:category, price=:price, image=:image WHERE id=:productid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_INT);
        $query->bindParam(':price', $price);
        $query->bindParam(':image', $image, PDO::PARAM_STR);
        $query->bindParam(':productid', $productId, PDO::PARAM_INT);
        
        $query->execute();
        $_SESSION['updatemsg'] = "Products updated successfully";
        header('location:manage-products.php');
    }
}

// Fetch product data for the form
$productData = [];
$categories = [];

try {
    // Get product details
    $sql = "SELECT products.name, categories.name AS category, categories.id AS catid, 
                   products.price, products.image 
            FROM products 
            LEFT JOIN categories ON categories.id = products.catid 
            WHERE products.id=:productid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':productid', $productId, PDO::PARAM_INT);
    $query->execute();
    $productData = $query->fetch(PDO::FETCH_OBJ) ?: [];

    // Get all categories for dropdown
    $sql1 = "SELECT * FROM categories";
    $query1 = $dbh->prepare($sql1);
    $query1->execute();
    $categories = $query1->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $errors[] = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>New Royal Flowers | Edit Product</title>
    <!-- Add your CSS includes here -->
</head>

<body>
    <?php include(__DIR__ . '/../includes/header.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Edit Product</h4>
                </div>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Product Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" enctype="multipart/form-data">
                                <?php if (!empty($productData)): ?>
                                    <input type="hidden" name="currentimage" 
                                           value="<?php echo htmlspecialchars($productData->image ?? ''); ?>">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Image</label><br>
                                            <?php if (!empty($productData->image)): ?>
                                                <img src="../productImg/<?php echo htmlspecialchars($productData->image); ?>" 
                                                     width="100" alt="Product Image"><br><br>
                                            <?php endif; ?>
                                            <input type="file" name="productimg" class="form-control" accept="image/*" />
                                            <small>If you don't select a new image, the current one will remain.</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Name<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" name="name"
                                                value="<?php echo htmlspecialchars($productData->name ?? ''); ?>" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category<span style="color:red;">*</span></label>
                                            <select class="form-control" name="category" required>
                                                <?php if (!empty($productData->catid)): ?>
                                                    <option value="<?php echo htmlspecialchars($productData->catid); ?>">
                                                        <?php echo htmlspecialchars($productData->category ?? 'Select Category'); ?>
                                                    </option>
                                                <?php endif; ?>
                                                
                                                <?php foreach ($categories as $category): ?>
                                                    <?php if (empty($productData->catid) || $category->id != $productData->catid): ?>
                                                        <option value="<?php echo htmlspecialchars($category->id); ?>">
                                                            <?php echo htmlspecialchars($category->name); ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price (USD)<span style="color:red;">*</span></label>
                                            <input class="form-control" type="number" step="0.01" name="price"
                                                value="<?php echo htmlspecialchars($productData->price ?? ''); ?>" required />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" name="update" class="btn btn-info">Update</button>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning">Product not found</div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include(__DIR__ . '/../includes/footer.php'); ?>
    
</body>
</html>