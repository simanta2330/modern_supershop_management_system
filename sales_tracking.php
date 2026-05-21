<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include('db.php');

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: admin.php");
    exit();
}

$filter = $_GET['filter'] ?? 'daily';
$custom_date = $_GET['date'] ?? date('Y-m-d');
$custom_week = $_GET['week'] ?? date('Y-W');
$custom_month = $_GET['month'] ?? date('Y-m');

if($filter == 'daily'){
    $date_label = "Daily – $custom_date";
    $where = "DATE(s.sale_date) = '$custom_date'";
    $group_by = "HOUR(s.sale_date)";
    $label_expr = "CONCAT(HOUR(s.sale_date), ':00')";
} elseif($filter == 'weekly'){
  
    $week_parts = explode('-W', $custom_week);
    $year = $week_parts[0];
    $week = $week_parts[1];
    $start = date('Y-m-d', strtotime("$year-W{$week}-1"));
    $end   = date('Y-m-d', strtotime("$year-W{$week}-7"));
    $date_label = "Weekly – $start to $end";
    $where = "DATE(s.sale_date) BETWEEN '$start' AND '$end'";
    $group_by = "DATE(s.sale_date)";
    $label_expr = "DATE(s.sale_date)";
} else {
  
    $date_label = "Monthly – $custom_month";
    $where = "DATE_FORMAT(s.sale_date,'%Y-%m') = '$custom_month'";
    $group_by = "DATE(s.sale_date)";
    $label_expr = "DATE(s.sale_date)";
}


$summary = $conn->query("
    SELECT
        COUNT(DISTINCT s.sale_id)  AS total_orders,
        COALESCE(SUM(s.grand_total), 0) AS total_revenue,
        COALESCE(SUM(s.discount), 0)    AS total_discount,
        COALESCE(SUM(
            (SELECT SUM(si2.qty * p2.cost_price)
             FROM sale_items si2
             JOIN products p2 ON si2.product_id = p2.product_id
             WHERE si2.sale_id = s.sale_id)
        ), 0) AS total_cost
    FROM sales s
    WHERE $where AND s.grand_total > 0
")->fetch_assoc();

$gross_profit = $summary['total_revenue'] - $summary['total_cost'];

$chart_res = $conn->query("
    SELECT $label_expr AS lbl,
           COALESCE(SUM(s.grand_total),0) AS revenue,
           COUNT(DISTINCT s.sale_id) AS orders
    FROM sales s
    WHERE $where AND s.grand_total > 0
    GROUP BY $group_by
    ORDER BY $group_by ASC
");
$chart_labels = [];
$chart_revenue = [];
$chart_orders = [];
while($r = $chart_res->fetch_assoc()){
    $chart_labels[]  = $r['lbl'];
    $chart_revenue[] = (float)$r['revenue'];
    $chart_orders[]  = (int)$r['orders'];
}

$pay_res = $conn->query("
    SELECT payment_method, COUNT(*) as cnt, COALESCE(SUM(grand_total),0) as total
    FROM sales s
    WHERE $where AND grand_total > 0
    GROUP BY payment_method
");

$top_res = $conn->query("
    SELECT p.product_name,
           SUM(si.qty) AS total_qty,
           SUM(si.qty * si.price) AS total_revenue
    FROM sale_items si
    JOIN products p ON si.product_id = p.product_id
    JOIN sales s ON si.sale_id = s.sale_id
    WHERE $where AND s.grand_total > 0
    GROUP BY si.product_id
    ORDER BY total_revenue DESC
    LIMIT 8
");

$recent_res = $conn->query("
    SELECT s.sale_id, s.sale_date, s.grand_total, s.payment_method,
           s.discount, c.full_name AS customer_name, u.full_name AS cashier_name
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.customer_id
    LEFT JOIN users u ON s.user_id = u.user_id
    WHERE $where AND s.grand_total > 0
    ORDER BY s.sale_date DESC
    LIMIT 15
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Sales Tracking – Supershop</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin-bottom: 20px;
}
.stat-card h6 { color: #666; font-size: 13px; margin-bottom: 6px; }
.stat-card h3 { font-weight: 700; margin: 0; }
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
.nav-link-custom.active { background: #0d6efd; }
.section-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}
</style>
</head>
<body>
<div class="overlay">
<div class="container-fluid">

<h2 class="text-white mb-3">📊 Sales Tracking</h2>

<div class="mb-4">
    <a href="admin.php" class="nav-link-custom">⬅ Admin Panel</a>
    <a href="sales_tracking.php?filter=daily" class="nav-link-custom <?= $filter=='daily'?'active':'' ?>">📅 Daily</a>
    <a href="sales_tracking.php?filter=weekly" class="nav-link-custom <?= $filter=='weekly'?'active':'' ?>">🗓 Weekly</a>
    <a href="sales_tracking.php?filter=monthly" class="nav-link-custom <?= $filter=='monthly'?'active':'' ?>">📆 Monthly</a>
</div>

<div class="section-card mb-4">
    <form method="get" class="row g-2 align-items-end">
        <input type="hidden" name="filter" value="<?= $filter ?>">
        <?php if($filter == 'daily'): ?>
            <div class="col-auto">
                <label class="form-label fw-bold">Select Date</label>
                <input type="date" name="date" class="form-control" value="<?= $custom_date ?>">
            </div>
        <?php elseif($filter == 'weekly'): ?>
            <div class="col-auto">
                <label class="form-label fw-bold">Select Week</label>
                <input type="week" name="week" class="form-control" value="<?= $custom_week ?>">
            </div>
        <?php else: ?>
            <div class="col-auto">
                <label class="form-label fw-bold">Select Month</label>
                <input type="month" name="month" class="form-control" value="<?= $custom_month ?>">
            </div>
        <?php endif; ?>
        <div class="col-auto">
            <button class="btn btn-primary">Filter</button>
        </div>
        <div class="col-auto align-self-center text-muted small mt-3">
            Showing: <strong><?= $date_label ?></strong>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="stat-card border-start border-4 border-primary">
            <h6> Total Revenue</h6>
            <h3 class="text-primary">৳ <?= number_format($summary['total_revenue'], 2) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-start border-4 border-success">
            <h6>Gross Profit</h6>
            <h3 class="text-success">৳ <?= number_format($gross_profit, 2) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-start border-4 border-warning">
            <h6> Total Orders</h6>
            <h3 class="text-warning"><?= $summary['total_orders'] ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card border-start border-4 border-danger">
            <h6>🏷 Total Discounts</h6>
            <h3 class="text-danger">৳ <?= number_format($summary['total_discount'], 2) ?></h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="section-card">
            <h5 class="fw-bold mb-3">Revenue Chart</h5>
            <?php if(empty($chart_labels)): ?>
                <p class="text-muted">No sales data for this period.</p>
            <?php else: ?>
            <canvas id="salesChart" height="100"></canvas>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="section-card">
            <h5 class="fw-bold mb-3"> Payment Methods</h5>
            <table class="table table-sm">
                <thead><tr><th>Method</th><th>Orders</th><th>Total</th></tr></thead>
                <tbody>
                <?php while($pr = $pay_res->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php
                            $icons = ['cash'=>'💵','card'=>'💳','mobile'=>'📱'];
                            echo ($icons[$pr['payment_method']] ?? '💰') . ' ' . ucfirst($pr['payment_method']);
                            ?>
                        </td>
                        <td><?= $pr['cnt'] ?></td>
                        <td>৳ <?= number_format($pr['total'], 0) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="section-card">
            <h5 class="fw-bold mb-3"> Top Selling Products</h5>
            <?php if($top_res->num_rows == 0): ?>
                <p class="text-muted">No data for this period.</p>
            <?php else: ?>
            <table class="table table-sm table-hover">
                <thead class="table-dark"><tr><th>#</th><th>Product</th><th>Qty Sold</th><th>Revenue</th></tr></thead>
                <tbody>
                <?php $rank=1; while($tr = $top_res->fetch_assoc()): ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><?= htmlspecialchars($tr['product_name']) ?></td>
                        <td><?= $tr['total_qty'] ?></td>
                        <td>৳ <?= number_format($tr['total_revenue'], 0) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="section-card">
            <h5 class="fw-bold mb-3"> Recent Transactions</h5>
            <?php if($recent_res->num_rows == 0): ?>
                <p class="text-muted">No transactions in this period.</p>
            <?php else: ?>
            <div style="max-height:350px; overflow-y:auto;">
            <table class="table table-sm table-hover">
                <thead class="table-dark"><tr><th>ID</th><th>Customer</th><th>Total</th><th>Pay</th><th>Date</th></tr></thead>
                <tbody>
                <?php while($rr = $recent_res->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $rr['sale_id'] ?></td>
                        <td><?= $rr['customer_name'] ? htmlspecialchars($rr['customer_name']) : 'Walk-in' ?></td>
                        <td>৳ <?= number_format($rr['grand_total'], 0) ?></td>
                        <td><?= ucfirst($rr['payment_method']) ?></td>
                        <td><?= date('d M, h:i A', strtotime($rr['sale_date'])) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</div>
</div>

<?php if(!empty($chart_labels)): ?>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [
            {
                label: 'Revenue (৳)',
                data: <?= json_encode($chart_revenue) ?>,
                backgroundColor: 'rgba(13,110,253,0.7)',
                borderColor: '#0d6efd',
                borderWidth: 1,
                yAxisID: 'y'
            },
            {
                label: 'Orders',
                data: <?= json_encode($chart_orders) ?>,
                type: 'line',
                borderColor: '#fd7e14',
                backgroundColor: 'rgba(253,126,20,0.15)',
                borderWidth: 2,
                pointRadius: 4,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: {
                type: 'linear',
                position: 'left',
                title: { display: true, text: 'Revenue (৳)' }
            },
            y1: {
                type: 'linear',
                position: 'right',
                title: { display: true, text: 'Orders' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});
</script>
<?php endif; ?>
</body>
</html>
