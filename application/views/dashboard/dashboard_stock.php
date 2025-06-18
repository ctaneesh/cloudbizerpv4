

<style>
  .card {
    transition: 0.3s ease-in-out;
  }

  

  .stat-card {
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    background: #fff;
    padding: 2rem 1rem 1rem 1rem;
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
  }

  .stat-icon-box {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 28px;
    position: absolute;
    top: -25px;
    left: 20px;
    z-index: 2;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  }

  .card-content-custom {
    flex: 1;
    text-align: right;
    margin-left: 100px;
    margin-top: -20px;
  }

  .card-category {
    font-size: 14px;
    color: #999;
    margin-bottom: 4px;
  }

  .card-value {
    font-size: 22px;
    font-weight: 600;
  }

  .bg-warning   { background-color: #ffa726; }
  .bg-success   { background-color: #66bb6a; }
  .bg-danger    { background-color: #ef5350; }
  .bg-info      { background-color: #26c6da; }
  .bg-mango     { background-color: #f6be4f; }
  .bg-deep-sea  { background-color: #05668d; }
  .bg-rosewood  { background-color: #a4133c; }
  .bg-citron    { background-color: #c4d35f; }
  .bg-peachy    { background-color: #ff9671; }
  .bg-pink      { background-color: #ec407a; }

  

  .summary-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #fff;
    border-radius: 8px;
    padding: 10px;
    margin-right: 2.5rem;
  }

</style>





  <div class="row g-3 mt-2">

    <!-- Card 1 -->
    <div class="col-md-3 mb-1">		
      <a href="<?= base_url('products') ?>" class="stat-link" >
        <div class="stat-card">
          <div class="stat-icon-box bg-pink"><i class="ft-list"></i></div>
          <div class="card-content-custom">
            <div class="card-category">Products</div>
            <div class="card-value"><?= $products['total']?></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Card 2 -->
    <div class="col-md-3 mb-1" >
      <a href="<?= base_url('purchase') ?>" class="stat-link" >
        <div class="stat-card">
          <div class="stat-icon-box bg-info"><i class="icon-handbag"></i></div>
          <div class="card-content-custom">
            <div class="card-category">Purchase Orders</div>
            <div class="card-value"><?= $purchase_order['total']?></div>
          </div>
        </div>
      </a>
    </div>

    <!-- Card 3 -->
    <div class="col-md-3 mb-1" >
      <a href="<?= base_url('purchasereturns') ?>" class="stat-link" >
        <div class="stat-card">
          <div class="stat-icon-box bg-mango"><i class="icon-puzzle"></i></div>
          <div class="card-content-custom">
            <div class="card-category">Purchase Returns</div>
            <div class="card-value"><?= $purchase_return['total']?></div>
          </div>
        </div>
      </a>
    </div>

	<!-- Card 4 -->
    <div class="col-md-3 mb-1" >
      <a href="<?= base_url('stocktransfer') ?>">
        <div class="stat-card">
          <div class="stat-icon-box bg-success"><i class="fa fa-exchange"></i></div>
          <div class="card-content-custom">
            <div class="card-category">Stock Transfer</div>
            <div class="card-value"><?= $stocks['total']?></div>
          </div>
        </div>
      </a>
    </div>

	<!-- Table 1 -->
    <div class="col-md-6">
        <div class="card">
				<div class="card-header">
					<h4 class="card-title">Recent Products</h4>
					<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
									<p><span class="float-right">
						<a href="<?= base_url('products/add') ?>" class="btn btn-primary btn-sm rounded" >Add</a>
						<a href="<?= base_url('products') ?>" class="btn btn-success btn-sm rounded">Manage</a>
						</span>
									</p>
					</div>
				</div>
			<div class="card-body ">
				<table class="table table-hover table-striped table-bordered zero-configuration dataTable">
					<thead>
					<tr><th>#</th><th>Name</th><th>Price</th><th>Status</th></tr>
					</thead>
					<tbody>
					    <?php if (!empty($products['product_list'])) : ?>
							<?php $i = 1;
								foreach ($products['product_list'] as $prod) : ?>
										<tr>
											<td><?= $i++; ?></td>
											<td>
												<a href="<?php echo base_url(); ?>products/add?code=<?=$prod->product_code?>">
												   <?= $prod->product_name; ?>
												</a>												
											</td>
											<td><?= $prod->product_price; ?></td>
											<td><?= $prod->status; ?></td>
										</tr>
										<?php endforeach; ?>
										<?php else : ?>
										<tr>
												<td colspan="4" class="text-center">No products found.</td>
										</tr>
							<?php endif; ?>
					</tbody>
				</table>
			</div>
        </div>
    </div>
	

   <!-- Chart Container -->
  <div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Stock Overview</h5>

				<!-- Chart Container -->
				<div >
					<canvas id="productChart"></canvas>
				</div>

				<!-- Legend Below Chart - Normal Flow -->
				<div style="
					
					display: flex;
					flex-direction: column;
					align-items: flex-start;
					font-size: 13px;
					line-height: 1.3;
					width: 150px;
					text-align: left;
					margin-left: auto;
					margin-right: auto;
				">
					<div><span style="color: #4CAF50; font-weight: bold;">■</span> In Stock <?= $product_stock_status['percentages'][0] ?>%</div>
					<div><span style="color: #F44336; font-weight: bold;">■</span> Out of Stock <?= $product_stock_status['percentages'][1] ?>%</div>

				</div>
			</div>
		</div>
  </div>




	<!-- Card 1 - Category -->
	<div class="col-md-4" >
		<a href="<?= base_url('productcategory') ?>" class="stat-link" >
	<div class="card text-primary shadow-sm" style="background-color: #e9f2fb;">
		<div class="card-body">
		<div class="d-flex justify-content-between align-items-center">
			<div>
			<h5 class="card-title mb-1">Categories</h5>
			<p class="card-text fs-4"><?= $categories['total']?></p>
			</div>
			<i class="fa fa-list fa-2x"></i>
		</div>
		</div>
	</div>
		</a>
	</div>

	<!-- Card 2 - Brands -->
	<div class="col-md-4">
		<a href="<?= base_url('brand') ?>" class="stat-link">
	<div class="card text-warning shadow-sm"  style="background-color: #fff9e6;">
		<div class="card-body">
		<div class="d-flex justify-content-between align-items-center">
			<div>
			<h5 class="card-title mb-1">Brands</h5>
			<p class="card-text fs-4"><?= $brands['total']?></p>
			</div>
			<i class="fa fa-tags fa-2x"></i>
		</div>
		</div>
	</div>
		</a>
	</div>

	<!-- Card 3 - Manufacturers -->
	<div class="col-md-4">
		<a href="<?= base_url('manufacturers') ?>" class="stat-link" >
	<div class="card text-success shadow-sm" style="background-color: #eafaf1;">
		<div class="card-body">
		<div class="d-flex justify-content-between align-items-center">
			<div>
			<h5 class="card-title mb-1">Manufacturers</h5>
			<p class="card-text fs-4"><?= $manufactrs['total']?></p>
			</div>
			<i class="fa fa-industry fa-2x"></i>
		</div>
		</div>
	</div>
		</a>
	</div>


    <!-- Chart 2 -->
    <div class="col-md-6" >
      <div class="card ">
        <div class="card-body position-relative">
          <h5 class="card-title">Purchase Order</h5>
		  <div style="height: 350px; ">
				<canvas id="purchaseOrderChart" ></canvas>
			</div>
        </div>
      </div>
    </div>

    

    <!-- Table 2 -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
				<h4 class="card-title">Recent Purchase Orders</h4>
				   <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
					<p><span class="float-right">
					<a href="<?= base_url('purchase/create') ?>" class="btn btn-primary btn-sm rounded" >Add</a>
					<a href="<?= base_url('purchase') ?>" class="btn btn-success btn-sm rounded">Manage</a>
					</span>
				</div>
			</div>
			<div class="card-body ">
				<table class="table table-hover table-striped table-bordered zero-configuration dataTable">
					<thead>
					<tr><th>#</th><th>Purchase No.</th><th>Purchase Date</th><th>Order Status</th></tr>
					</thead>
					<tbody>
					    <?php if (!empty($purchase_order['purchase_orders_list'])) : ?>
							<?php $i = 1;
								foreach ($purchase_order['purchase_orders_list'] as $pord) : ?>
										<tr>
											<td><?= $i++; ?></td>
											<td>
												<a href="<?php echo base_url(); ?>purchase/create?id=<?=$pord->purchase_number?>">
												   <?= $pord->purchase_number; ?>
												</a>
												
											</td>
											<td><?= date('d-m-Y', strtotime($pord->purchase_order_date)); ?></td>
											<td><?= $pord->order_status; ?></td>
										</tr>
										<?php endforeach; ?>
										<?php else : ?>
										<tr>
												<td colspan="4" class="text-center">No data found.</td>
										</tr>
							<?php endif; ?>
					</tbody>
				</table>
			</div>
      </div>
    </div>

	<div class="col-md-4 ">
    <a href="<?= base_url('Invoices/stockreciepts') ?>" >
        <div class="card text-dark border border-warning shadow">

            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="me-3">
                    <div class="summary-icon bg-warning"><i class="fa fa-file"></i></div>
                </div>
                <div>
                    <h6 class="mb-0">Purchase Receipts</h6>
                    <strong><?= $purchs_recipts['total'] ?></strong>
                </div>
            </div>
        </div>
    </a>
</div>


	<div class="col-md-4">
		<a href="<?= base_url('products/custom_label') ?>" >
			<div class="card text-dark border border-info shadow">
			<div class="card-body d-flex justify-content-between align-items-center">
				<div class="me-3">
				<div class="summary-icon bg-info"><i class="fa fa-tags"></i></div>
				</div>
				<div>
				<h6 class="mb-0">Custome Label</h6>
				<!-- <strong><?= $cutomers['active'] ?></strong> -->
				</div>
			</div>
			</div>
		</a>
	</div>

	<div class="col-md-4">
		<a href="<?= base_url('products/standard_label') ?>" >
			<div class="card text-dark border border-success shadow">
				<div class="card-body d-flex justify-content-between align-items-center">
					<div class="me-3">
						<div class="summary-icon bg-success"><i class="fa fa-tags"></i></div>
					</div>
					<div>
						<h6 class="mb-0">Standard Label</h6>
						<!-- <strong><?= $cutomers['year'] ?></strong> -->
					</div>
				</div>
			</div>
		</a>
	</div>

	<!-- Table 2 -->
    <div class="col-md-6 ">
      <div class="card">
        <div class="card-header">
				<h4 class="card-title">Recent Purchase Returns</h4>
				<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                
				<div class="heading-elements">
                    <p><span class="float-right"> 
					<a href="<?= base_url('purchasereturns/create') ?>" class="btn btn-primary btn-sm rounded me-1 " >Add</a>
					<a href="<?= base_url('purchasereturns') ?>" class="btn btn-success btn-sm rounded">Manage</a>
					</span>
										</p>
				</div>
			</div>
			<div class="card-body ">
				<table class="table table-hover table-striped table-bordered zero-configuration dataTable">
					<thead>
					<tr><th>#</th><th>Purchase Rec No.</th><th>Return Date</th><th>Return Status</th></tr>
					</thead>
					<tbody>
    <?php if (!empty($purchase_return['return_list'])) { ?>
        <?php $i = 1;
        foreach ($purchase_return['return_list'] as $purchsrtn) {
            // If you need token in future, uncomment below:
             $validtoken = hash_hmac('ripemd160', 'p' . $purchsrtn->id, $this->config->item('encryption_key'));
						//$validtoken = hash_hmac('ripemd160', 'p' . $invoice['iid'], $this->config->item('encryption_key'));
        ?>
            <tr>
							<td><?= $i++; ?></td>
							<td>
									<!-- Uncomment below if link needed -->
									
									<a href="<?= base_url("purchasereturns/create?pid={$purchsrtn->purchase_reciept_number}&token={$validtoken}") ?>">
											<?= htmlspecialchars($purchsrtn->purchase_reciept_number); ?>
									</a>
									
									<!-- <?= htmlspecialchars($purchsrtn->purchase_reciept_number); ?> -->
							</td>
							<td><?= date('d-m-Y', strtotime($purchsrtn->return_date)); ?></td>
							<td><?= htmlspecialchars($purchsrtn->return_status); ?></td>
					</tr>

        <?php } ?>
        <?php } else { ?>
        <tr>
            <td colspan="4" class="text-center">No data found.</td>
        </tr>
    <?php } ?>
</tbody>


				</table>
			</div>
      </div>
    </div>

	<!-- Chart 2 -->
    <div class="col-md-6">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Purchase Returns</h5>
      <div style="display: flex; justify-content: center; align-items: center; height: 350px;">
        <canvas id="purchaseReturnPieChart" width="280" height="280"></canvas>
      </div>
    </div>
  </div>
</div>


	<div class="col-md-3">
		<a href="<?= base_url('reports/stock_report') ?>">
			<div class="card text-white bg-warning">
				<div class="card-body d-flex justify-content-between align-items-center">
					<div class="me-3">
						<i class="fa fa-calendar fa-2x text-white"></i>
					</div>
					<div>
						<h6 class="mb-0">Stock Report</h6>
						<!-- <strong><?= $cutomers['month'] ?></strong> -->
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-md-3">
		<a href="<?= base_url('pos_invoices/extended') ?>" >
			<div class="card text-white bg-info">
			<div class="card-body d-flex justify-content-between align-items-center">
				<div class="me-3">
				<i class="fa fa-users fa-2x text-white"></i>
				</div>
				<div>
				<h6 class="mb-0">Purchase Sales Report</h6>
				<!-- <strong><?= $cutomers['active'] ?></strong> -->
				</div>
			</div>
			</div>
		</a>
	</div>

	<div class="col-md-3">
		<a href="<?= base_url('reports/purchase_orders_report') ?>" >
			<div class="card text-white bg-success">
				<div class="card-body d-flex justify-content-between align-items-center">
					<div class="me-3">
						<i class="fa fa-calendar fa-2x text-white"></i>
					</div>
					<div>
						<h6 class="mb-0">Open Purchase Orders</h6>
						<!-- <strong><?= $cutomers['year'] ?></strong> -->
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-md-3 ">
		<a href="<?= base_url('reports/purchase_orders_tree_report') ?>">
			<div class="card text-white bg-danger">
			<div class="card-body d-flex justify-content-between align-items-center">
				<div class="me-3">
				<i class="fa fa-calendar fa-2x text-white"></i>
				</div>
				<div>
				<h6 class="mb-0">Purchase Order Tree</h6>
				<!-- <strong><?= $cutomers['active'] ?></strong> -->
				</div>
			</div>
			</div>
		</a>
	</div>

    

    

  </div>

  <!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ApexCharts CDN (not used but included) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  const prd = document.getElementById('productChart').getContext('2d');

  const dataValues = <?= json_encode($product_stock_status['values']) ?>;
  const total = <?= $product_stock_status['total'] ?>;

  new Chart(prd, {
    type: 'doughnut',
    data: {
      labels: ['In Stock', 'Out of Stock'],
      datasets: [{
        data: dataValues,
        backgroundColor: ['#4CAF50', '#F44336'],
        borderWidth: 0,
        borderRadius: 15,
        cutout: '70%'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      rotation: -90,
      circumference: 180,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function (context) {
              let label = context.label || '';
              let value = context.raw;
              let percentage = ((value / total) * 100).toFixed(2);
              return `${label}: ${percentage}%`;
            }
          }
        },
      }
    },
    plugins: [{
      id: 'centerText',
      beforeDraw: function (chart) {
        const { width, height, ctx } = chart;
        ctx.restore();
        const fontSize = 16;
        ctx.font = `bold ${fontSize}px sans-serif`;
        ctx.textBaseline = 'middle';

        const text = 'Total Stocks';
        const textX = (width - ctx.measureText(text).width) / 2;
        ctx.fillStyle = '#000';
        ctx.fillText(text, textX, height / 2 - 10);

        const value = total.toString();
        ctx.font = `bold 26px sans-serif`;
        const valueX = (width - ctx.measureText(value).width) / 2;
        ctx.fillText(value, valueX, height / 2 + 20);
        ctx.save();
      }
    }]
  });
</script>





<script>
  const monthlyPurchaseOrdrData = <?= json_encode($purchase_order['monthly_data']) ?>;

  function getLast12MonthsLabels() {
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const labels = [];
    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();

    for (let i = 11; i >= 0; i--) {
      const d = new Date(currentYear, currentMonth - i, 1);
      labels.push(`${monthNames[d.getMonth()]} ${d.getFullYear()}`);
    }
    return labels;
  }

  //console.log("Customer Data:", monthlyPurchaseOrdrData);

  const ctxx = document.getElementById('purchaseOrderChart').getContext('2d');

  new Chart(ctxx, {
    type: 'bar',
    data: {
      labels: getLast12MonthsLabels(),
      datasets: [{
        label: 'Purchase Orders',
        data: monthlyPurchaseOrdrData,
        backgroundColor: '#9C27B0' 

      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: {
            usePointStyle: true,
            boxWidth: 10,
            boxHeight: 10
          }
        }
      },
      scales: {
        x: {
          grid: { display: false }
        },
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 20
          }
        }
      }
    }
  });
</script>

<script>
  const monthlyData = <?= json_encode($purchase_return['monthly_data']) ?>;

  const labels = monthlyData.map(item => item.label);
  const values = monthlyData.map(item => item.count);

  const ctx = document.getElementById('purchaseReturnPieChart')?.getContext('2d');

  if (ctx) {
    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: labels,
        datasets: [{
          data: values,
          backgroundColor: [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
            '#858796', '#fd7e14', '#20c997', '#6f42c1', '#d63384',
            '#0dcaf0', '#198754'
          ]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw;
                return `${label}: ${value} Returns`;
              }
            }
          },
          title: {
            display: true,
            text: 'Purchase Returns (Last 12 Months)'
          }
        }
      }
    });
  }
</script>



