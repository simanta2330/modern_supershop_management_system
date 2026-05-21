<?php
session_start();
include('db.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title> Stock Management | Saudia Super Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
            min-height: 100vh;
        }

        .stock-container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .header-card {
            background: white;
            border-radius: 25px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .header-card h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
            font-weight: bold;
        }

        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .stat-icon {
            font-size: 45px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        
        .stock-card {
            background: white;
            border-radius: 25px;
            padding: 25px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .stock-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }

        .stock-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stock-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: bold;
        }

        .stock-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .stock-table tr:hover {
            background: #f8f9ff;
        }

        
        .badge-low {
            background: #e74c3c;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-ok {
            background: #2ecc71;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-warning {
            background: #f39c12;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        
        .row-critical {
            background: #ffe0e0;
            border-left: 5px solid #e74c3c;
        }

        .row-low {
            background: #fff3e0;
            border-left: 5px solid #f39c12;
        }

        .row-ok {
            background: #e0ffe0;
            border-left: 5px solid #2ecc71;
        }

       
        .movement-card {
            background: white;
            border-radius: 25px;
            padding: 25px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .movement-table {
            width: 100%;
            border-collapse: collapse;
        }

        .movement-table th {
            background: #2c3e50;
            color: white;
            padding: 10px;
        }

        .movement-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

       
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            color: white;
            font-weight: bold;
            margin: 5px;
            transition: transform 0.3s;
        }

        .btn-gradient:hover {
            transform: scale(1.05);
            color: white;
        }

        .btn-dark-custom {
            background: #2c3e50;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            color: white;
            font-weight: bold;
            margin: 5px;
        }

        
        .stock-progress {
            width: 100%;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            height: 8px;
        }

        .stock-progress-bar {
            height: 8px;
            border-radius: 10px;
            transition: width 0.3s;
        }

        .progress-green { background: #2ecc71; }
        .progress-yellow { background: #f39c12; }
        .progress-red { background: #e74c3c; }

        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>

<div class="stock-container animate">
    
   
    <div class="header-card">
        <h1><i class="fas fa-boxes"></i> Stock Management System</h1>
        <p class="text-muted">Real-time inventory tracking & management</p>
        <div class="mt-3">
            <a href="index.php" class="btn-gradient"><i class="fas fa-store"></i> Back to Shop</a>
            <a href="admin.php" class="btn-dark-custom"><i class="fas fa-user-shield"></i> Admin Panel</a>
            <button onclick="location.reload()" class="btn-gradient"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>
    </div>

  
    <?php
    $total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
    $total_stock = $conn->query("SELECT SUM(stock_qty) as total FROM products")->fetch_assoc()['total'];
    $low_stock_count = $conn->query("SELECT COUNT(*) as count FROM products WHERE stock_qty <= reorder_level")->fetch_assoc()['count'];
    $critical_stock = $conn->query("SELECT COUNT(*) as count FROM products WHERE stock_qty <= reorder_level/2")->fetch_assoc()['count'];
    ?>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-tag" style="color: #667eea;"></i></div>
                <div class="stat-number"><?= $total_products ?></div>
                <div class="stat-label">Total Products</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-box" style="color: #2ecc71;"></i></div>
                <div class="stat-number"><?= number_format($total_stock) ?></div>
                <div class="stat-label">Total Stock Items</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle" style="color: #f39c12;"></i></div>
                <div class="stat-number"><?= $low_stock_count ?></div>
                <div class="stat-label">Low Stock Alerts</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-skull-crossbones" style="color: #e74c3c;"></i></div>
                <div class="stat-number"><?= $critical_stock ?></div>
                <div class="stat-label">Critical Stock</div>
            </div>
        </div>
    </div>

    
    <div class="stock-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="stock-title">
                <i class="fas fa-clipboard-list"></i> Current Inventory Status
            </div>
            <div>
                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search product..." style="border-radius: 50px;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="stock-table" id="stockTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-box"></i> Product Name</th>
                        <th><i class="fas fa-layer-group"></i> Current Stock</th>
                        <th><i class="fas fa-flag-checkered"></i> Reorder Level</th>
                        <th><i class="fas fa-chart-line"></i> Stock Status</th>
                        <th><i class="fas fa-chart-simple"></i> Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $products = $conn->query("SELECT p.*, c.category_name 
                                              FROM products p 
                                              LEFT JOIN categories c ON p.category_id = c.category_id 
                                              ORDER BY p.product_id");
                    
                    while($row = $products->fetch_assoc()):
                        $stock = $row['stock_qty'];
                        $reorder = $row['reorder_level'];
                        $percentage = ($reorder > 0) ? min(100, ($stock / $reorder) * 100) : 100;
                        
                        if($stock <= 0):
                            $row_class = 'row-critical';
                            $badge = '<span class="badge-low"><i class="fas fa-skull-crossbones"></i> CRITICAL (Out of Stock)</span>';
                            $progress_class = 'progress-red';
                        elseif($stock <= $reorder):
                            $row_class = 'row-low';
                            $badge = '<span class="badge-warning"><i class="fas fa-exclamation-triangle"></i> LOW STOCK</span>';
                            $progress_class = 'progress-yellow';
                        else:
                            $row_class = 'row-ok';
                            $badge = '<span class="badge-ok"><i class="fas fa-check-circle"></i> SUFFICIENT</span>';
                            $progress_class = 'progress-green';
                        endif;
                    ?>
                    <tr class="<?= $row_class ?>">
                        <td><?= $row['product_id'] ?></td>
                        <td><strong><?= htmlspecialchars($row['product_name']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($row['category_name']) ?></small>
                        </td>
                        <td>
                            <span style="font-size: 24px; font-weight: bold; color: <?= ($stock <= $reorder) ? '#e74c3c' : '#2ecc71' ?>">
                                <?= number_format($stock) ?>
                            </span>
                            <small>units</small>
                        </td>
                        <td><?= number_format($reorder) ?> units</td>
                        <td><?= $badge ?></td>
                        <td>
                            <div class="stock-progress">
                                <div class="stock-progress-bar <?= $progress_class ?>" style="width: <?= $percentage ?>%"></div>
                            </div>
                            <small><?= round($percentage) ?>% of reorder level</small>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="movement-card">
        <div class="stock-title">
            <i class="fas fa-history"></i> Recent Stock Movements
        </div>
        <div class="table-responsive">
            <table class="movement-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-clock"></i> Date & Time</th>
                        <th><i class="fas fa-box"></i> Product</th>
                        <th><i class="fas fa-exchange-alt"></i> Type</th>
                        <th><i class="fas fa-calculator"></i> Quantity</th>
                        <th><i class="fas fa-info-circle"></i> Reference</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $movements = $conn->query("SELECT sm.*, p.product_name 
                                               FROM stock_movements sm 
                                               JOIN products p ON sm.product_id = p.product_id 
                                               ORDER BY sm.moved_at DESC LIMIT 20");
                    
                    if($movements->num_rows > 0):
                        while($move = $movements->fetch_assoc()):
                            $type_icon = ($move['type'] == 'sale_out') ? '<i class="fas fa-arrow-down" style="color:#e74c3c;"></i>' : '<i class="fas fa-arrow-up" style="color:#2ecc71;"></i>';
                            $type_text = ($move['type'] == 'sale_out') ? '📤 Sale Out' : '📥 Purchase In';
                    ?>
                    <tr>
                        <td><?= date('d/m/Y h:i A', strtotime($move['moved_at'])) ?></td>
                        <td><?= htmlspecialchars($move['product_name']) ?></td>
                        <td><?= $type_icon ?> <?= $type_text ?></td>
                        <td><strong><?= $move['quantity'] ?></strong> units</td>
                        <td><?= $move['reference_id'] ? 'Order #' . $move['reference_id'] : '-' ?></td>
                    </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">📭 No stock movements recorded yet. Place an order to see updates.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
   
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#stockTable tbody tr');
        
        rows.forEach(row => {
            let productName = row.cells[1].innerText.toLowerCase();
            if(productName.indexOf(filter) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>