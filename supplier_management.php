<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include('db.php');

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: admin.php");
    exit();
}

$msg = '';
$error = '';

if(isset($_POST['add_supplier'])){
    $company  = $conn->real_escape_string(trim($_POST['company_name']));
    $contact  = $conn->real_escape_string(trim($_POST['contact_name']));
    $phone    = $conn->real_escape_string(trim($_POST['phone']));
    $email    = $conn->real_escape_string(trim($_POST['email']));
    $address  = $conn->real_escape_string(trim($_POST['address']));

    if(empty($company)){
        $error = "Company name is required.";
    } else {
        $conn->query("INSERT INTO suppliers(company_name, contact_name, phone, email, address)
                      VALUES('$company','$contact','$phone','$email','$address')");
        $msg = " Supplier '$company' added successfully!";
    }
}

if(isset($_POST['update_supplier'])){
    $id       = (int)$_POST['supplier_id'];
    $company  = $conn->real_escape_string(trim($_POST['company_name']));
    $contact  = $conn->real_escape_string(trim($_POST['contact_name']));
    $phone    = $conn->real_escape_string(trim($_POST['phone']));
    $email    = $conn->real_escape_string(trim($_POST['email']));
    $address  = $conn->real_escape_string(trim($_POST['address']));

    $conn->query("UPDATE suppliers SET
                    company_name='$company',
                    contact_name='$contact',
                    phone='$phone',
                    email='$email',
                    address='$address'
                  WHERE supplier_id=$id");
    $msg = " Supplier updated successfully!";
}

if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
  
    $check = $conn->query("SELECT COUNT(*) as c FROM products WHERE supplier_id=$id")->fetch_assoc();
    if($check['c'] > 0){
        $error = "❌ Cannot delete: This supplier has " . $check['c'] . " product(s) linked. Reassign products first.";
    } else {
        $conn->query("DELETE FROM suppliers WHERE supplier_id=$id");
        $msg = " Supplier deleted.";
    }
}

$edit_supplier = null;
if(isset($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_res = $conn->query("SELECT * FROM suppliers WHERE supplier_id=$edit_id");
    $edit_supplier = $edit_res->fetch_assoc();
}

$search = $conn->real_escape_string($_GET['search'] ?? '');
$search_where = $search ? "WHERE company_name LIKE '%$search%' OR contact_name LIKE '%$search%' OR phone LIKE '%$search%'" : "";

$suppliers = $conn->query("SELECT * FROM suppliers $search_where ORDER BY company_name ASC");

function getSupplierStats($conn, $supplier_id){
    $prod = $conn->query("SELECT COUNT(*) as c FROM products WHERE supplier_id=$supplier_id")->fetch_assoc();
    $purch = $conn->query("SELECT COUNT(*) as orders, COALESCE(SUM(total_amount),0) as total FROM purchases WHERE supplier_id=$supplier_id")->fetch_assoc();
    return [
        'products' => $prod['c'],
        'orders'   => $purch['orders'],
        'total'    => $purch['total']
    ];
}

$view_supplier = null;
$purchase_history = null;
if(isset($_GET['view'])){
    $view_id = (int)$_GET['view'];
    $vres = $conn->query("SELECT * FROM suppliers WHERE supplier_id=$view_id");
    $view_supplier = $vres->fetch_assoc();
    $purchase_history = $conn->query("
        SELECT pu.purchase_id, pu.purchase_date, pu.total_amount, pu.note,
               u.full_name AS recorded_by
        FROM purchases pu
        LEFT JOIN users u ON pu.user_id = u.user_id
        WHERE pu.supplier_id = $view_id
        ORDER BY pu.purchase_date DESC
    ");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Supplier Management – Supershop</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: url('img/bg.jpg') no-repeat center center fixed;
    background-size: cover;
}
.overlay {
    background: rgba(0,0,0,0.75);
    min-height: 100vh;
    padding: 20px;
}
.section-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}
.nav-link-custom {
    color: white;
    margin-right: 8px;
    text-decoration: none;
    padding: 8px 14px;
    background: #333;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 6px;
}
.nav-link-custom:hover { background: #555; color: white; }
.badge-stat {
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 20px;
}
</style>
</head>
<body>
<div class="overlay">
<div class="container-fluid">

<h2 class="text-white mb-3"> Supplier Management</h2>

<!-- Nav -->
<div class="mb-4">
    <a href="admin.php" class="nav-link-custom"> Admin Panel</a>
    <a href="supplier_management.php" class="nav-link-custom"> All Suppliers</a>
    <a href="supplier_management.php#add-form" class="nav-link-custom" style="background:#198754;">➕ Add Supplier</a>
</div>

<?php if($msg): ?>
    <div class="alert alert-success"><?= $msg ?></div>
<?php endif; ?>
<?php if($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if($view_supplier): ?>
<div class="section-card">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h4>🏭 <?= htmlspecialchars($view_supplier['company_name']) ?></h4>
            <p class="mb-1">👤 Contact: <strong><?= htmlspecialchars($view_supplier['contact_name'] ?? 'N/A') ?></strong></p>
            <p class="mb-1">📞 Phone: <?= htmlspecialchars($view_supplier['phone'] ?? 'N/A') ?></p>
            <p class="mb-1">📧 Email: <?= htmlspecialchars($view_supplier['email'] ?? 'N/A') ?></p>
            <p class="mb-1">📍 Address: <?= htmlspecialchars($view_supplier['address'] ?? 'N/A') ?></p>
            <p class="text-muted small">Added: <?= date('d M Y', strtotime($view_supplier['created_at'])) ?></p>
        </div>
        <a href="supplier_management.php" class="btn btn-secondary">✖ Close</a>
    </div>

    <hr>
    <h5> Purchase History</h5>
    <?php if($purchase_history->num_rows == 0): ?>
        <p class="text-muted">No purchases recorded from this supplier yet.</p>
    <?php else: ?>
    <table class="table table-sm table-hover">
        <thead class="table-dark">
            <tr><th>Purchase ID</th><th>Date</th><th>Total Amount</th><th>Note</th><th>Recorded By</th></tr>
        </thead>
        <tbody>
        <?php $grand=0; while($ph = $purchase_history->fetch_assoc()): $grand += $ph['total_amount']; ?>
            <tr>
                <td>#<?= $ph['purchase_id'] ?></td>
                <td><?= date('d M Y, h:i A', strtotime($ph['purchase_date'])) ?></td>
                <td>৳ <?= number_format($ph['total_amount'], 2) ?></td>
                <td><?= htmlspecialchars($ph['note'] ?? '') ?></td>
                <td><?= htmlspecialchars($ph['recorded_by'] ?? '') ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr class="table-success fw-bold">
                <td colspan="2">Total</td>
                <td>৳ <?= number_format($grand, 2) ?></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
    <?php endif; ?>

    <hr>
    <h5> Linked Products</h5>
    <?php
    $linked_prods = $conn->query("
        SELECT p.product_name, p.selling_price, p.stock_qty, p.unit, c.category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        WHERE p.supplier_id = {$view_supplier['supplier_id']}
        ORDER BY p.product_name ASC
    ");
    if($linked_prods->num_rows == 0):
    ?>
        <p class="text-muted">No products linked to this supplier.</p>
    <?php else: ?>
    <table class="table table-sm">
        <thead class="table-secondary"><tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Unit</th></tr></thead>
        <tbody>
        <?php while($lp = $linked_prods->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($lp['product_name']) ?></td>
                <td><?= htmlspecialchars($lp['category_name'] ?? '') ?></td>
                <td>৳ <?= number_format($lp['selling_price'], 2) ?></td>
                <td><?= $lp['stock_qty'] ?></td>
                <td><?= $lp['unit'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="section-card" id="add-form">
    <h5 class="fw-bold mb-3"><?= $edit_supplier ? '✏️ Edit Supplier' : '➕ Add New Supplier' ?></h5>
    <form method="post">
        <?php if($edit_supplier): ?>
            <input type="hidden" name="supplier_id" value="<?= $edit_supplier['supplier_id'] ?>">
        <?php endif; ?>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                <input type="text" name="company_name" class="form-control" required
                       value="<?= htmlspecialchars($edit_supplier['company_name'] ?? '') ?>"
                       placeholder="e.g. Pran-RFL Group">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Contact Person</label>
                <input type="text" name="contact_name" class="form-control"
                       value="<?= htmlspecialchars($edit_supplier['contact_name'] ?? '') ?>"
                       placeholder="e.g. Md. Habibur Rahman">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Phone</label>
                <input type="text" name="phone" class="form-control"
                       value="<?= htmlspecialchars($edit_supplier['phone'] ?? '') ?>"
                       placeholder="01XXXXXXXXX">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($edit_supplier['email'] ?? '') ?>"
                       placeholder="supplier@company.com">
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Address</label>
                <input type="text" name="address" class="form-control"
                       value="<?= htmlspecialchars($edit_supplier['address'] ?? '') ?>"
                       placeholder="City, District">
            </div>
        </div>
        <div class="mt-3">
            <?php if($edit_supplier): ?>
                <button name="update_supplier" class="btn btn-warning"> Update Supplier</button>
                <a href="supplier_management.php" class="btn btn-secondary ms-2">Cancel</a>
            <?php else: ?>
                <button name="add_supplier" class="btn btn-success"> Add Supplier</button>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="section-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"> All Suppliers</h5>
        <form method="get" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Search by name / contact / phone"
                   value="<?= htmlspecialchars($search) ?>" style="width:280px;">
            <button class="btn btn-sm btn-primary">Search</button>
            <?php if($search): ?>
                <a href="supplier_management.php" class="btn btn-sm btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if($suppliers->num_rows == 0): ?>
        <p class="text-muted">No suppliers found<?= $search ? " matching \"$search\"" : "" ?>.</p>
    <?php else: ?>
    <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Company</th>
                <th>Contact</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Products</th>
                <th>Purchases</th>
                <th>Total Spent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($sup = $suppliers->fetch_assoc()):
            $stats = getSupplierStats($conn, $sup['supplier_id']);
        ?>
        <tr>
            <td><?= $sup['supplier_id'] ?></td>
            <td>
                <strong><?= htmlspecialchars($sup['company_name']) ?></strong><br>
                <small class="text-muted">📍 <?= htmlspecialchars($sup['address'] ?? 'N/A') ?></small>
            </td>
            <td><?= htmlspecialchars($sup['contact_name'] ?? '–') ?></td>
            <td><?= htmlspecialchars($sup['phone'] ?? '–') ?></td>
            <td><?= htmlspecialchars($sup['email'] ?? '–') ?></td>
            <td>
                <span class="badge bg-info text-dark"><?= $stats['products'] ?> products</span>
            </td>
            <td>
                <span class="badge bg-warning text-dark"><?= $stats['orders'] ?> orders</span>
            </td>
            <td>
                <strong>৳ <?= number_format($stats['total'], 0) ?></strong>
            </td>
            <td>
                <a href="supplier_management.php?view=<?= $sup['supplier_id'] ?>"
                   class="btn btn-sm btn-info mb-1" title="View Details">👁 View</a>
                <a href="supplier_management.php?edit=<?= $sup['supplier_id'] ?>#add-form"
                   class="btn btn-sm btn-warning mb-1" title="Edit">✏️ Edit</a>
                <a href="supplier_management.php?delete=<?= $sup['supplier_id'] ?>"
                   class="btn btn-sm btn-danger mb-1"
                   onclick="return confirm('Delete this supplier? This cannot be undone.')"
                   title="Delete">🗑</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

</div>
</div>
</body>
</html>
