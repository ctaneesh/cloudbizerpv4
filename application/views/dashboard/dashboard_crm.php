<!-- Include in your layout if not already -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<style>
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

  .stat-link {
    text-decoration: none;
    display: block;
    color: inherit;
  }

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

	.card a:not(.btn) {
  color: inherit !important;
  text-decoration: none !important;
}

.card a:not(.btn):hover {
  color: inherit !important;
}


</style>

<div class="mt-3">
  <!-- Stat Cards -->
  <div class="row">
    <div class="col-md-2 col-sm-4 mb-2">
      <a href="<?= base_url('customers') ?>" class="stat-link" >
        <div class="stat-card">
          <div class="stat-icon-box bg-pink"><i class="ft-users"></i></div>
          <div class="card-content-custom">
            <div class="card-category">Customers</div>
            <div class="card-value"><?= $cutomers['total'] ?></div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3 col-sm-4 mb-2">
      <a href="<?= base_url('clientgroup') ?>" class="stat-link" >
        <div class="stat-card">
          <div class="stat-icon-box bg-info"><i class="ft-users"></i></div>
          <div class="card-content-custom">
            <div class="card-category">Customer Groups</div>
            <div class="card-value"><?= $customer_group['group_total'] ?></div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-2 col-sm-4 mb-2">
      <a href="<?= base_url('invoices/leads') ?>" class="stat-link" >
        <div class="stat-card">
          <div class="stat-icon-box bg-mango"><i class="fa fa-usd"></i></div>
          <div class="card-content-custom">
            <div class="card-category">Leads</div>
            <div class="card-value"><?= $leads['total'] ?></div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-2 col-sm-4 mb-2">
      <a href="<?= base_url('supplier') ?>" class="stat-link" >
        <div class="stat-card">
          <div class="stat-icon-box bg-success"><i class="ft-target"></i></div>
          <div class="card-content-custom">
            <div class="card-category">Suppliers</div>
            <div class="card-value"><?= $suppliers['total'] ?></div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3 col-sm-4 mb-2">
      <a href="<?= base_url('invoices/leads') ?>" class="stat-link" >
        <div class="stat-card">
          <div class="stat-icon-box bg-citron"><i class="fa fa-ticket"></i></div>
          <div class="card-content-custom">
            <div class="card-category">Support Tickets</div>
            <div class="card-value"><?= $ticket_data['total'] ?></div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Leads Section -->
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Lead</h5>
    </div>
    <div class="card-body">
      <div class="row d-flex align-items-stretch mt-1">
					<!-- Summary Cards -->
					<div class="col-md-3 d-flex">
						<div class="d-flex flex-column w-100 gap-3">
							<a href="<?= base_url('invoices/leads') ?>"  >
							<div class="card shadow-sm border flex-fill">
								<div class="card-body d-flex align-items-center">
									<div class="summary-icon bg-primary"><i class="fa fa-file-text-o"></i></div>
									<div>
										<h6 class="mb-0">Today's Leads</h6>
										<strong><?= $leads['today'] ?></strong>
									</div>
								</div>
							</div>
							</a>
							<a href="<?= base_url('invoices/leads') ?>" >
							<div class="card shadow-sm border flex-fill">
								<div class="card-body d-flex align-items-center">
									<div class="summary-icon bg-warning"><i class="fa fa-shopping-basket"></i></div>
									<div>
										<h6 class="mb-0">Week Leads</h6>
										<strong><?= $leads['week'] ?></strong>
									</div>
								</div>
							</div>
							</a>

							<a href="<?= base_url('invoices/leads') ?>" >
							<div class="card shadow-sm border flex-fill">
								<div class="card-body d-flex align-items-center">
									<div class="summary-icon bg-danger"><i class="fa fa-calendar"></i></div>
									<div>
										<h6 class="mb-0">This Month Leads</h6>
										<strong><?= $leads['month'] ?></strong>
									</div>
								</div>
							</div>
							</a>
							
							<a href="<?= base_url('invoices/leads') ?>" >

							<div class="card shadow-sm border flex-fill">
								<div class="card-body d-flex align-items-center">
									<div class="summary-icon bg-success"><i class="fa fa-calendar"></i></div>
									<div>
										<h6 class="mb-0">This Year Leads</h6>
										<strong><?= $leads['year'] ?></strong>
									</div>
								</div>
							</div>
							</a>

						</div>
					</div>

					<!-- Recent Leads Table -->
					<div class="col-md-5">
						<div class="card ">
							<div class="card-header">
								<h4 class="card-title">Recent Leads</h4>
								<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <p>
												<span class="float-right"> 
													<a href="<?= base_url('invoices/customer_leads') ?>" class="btn btn-primary btn-sm rounded" >Add</a>
													<a href="<?= base_url('invoices/leads') ?>" class="btn btn-success btn-sm rounded">Manage</a>
												</span>
										</p>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-hover table-striped table-bordered zero-configuration dataTable">
									<thead>
										<tr>
											<th>#</th>
											<th>Lead #</th>
											<th>Created</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($leads['enquiries'])) : ?>
											<?php $i = 1;
											foreach ($leads['enquiries'] as $lead) : ?>
												<tr>
													<td><?= $i++; ?></td>
													<td>
														<a href="<?php echo base_url(); ?>invoices/customer_leads?id=<?=$lead->lead_id?>">
														<?= htmlspecialchars($lead->lead_number); ?>
														</a>
													</td>
													<td><?= date('d-m-Y', strtotime($lead->created_date)); ?></td>
													<td><?= htmlspecialchars($lead->enquiry_status); ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td colspan="4" class="text-center">No leads found.</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<!-- Leads Chart -->
					<div class="col-md-4 d-flex">
						<div class="card flex-fill">
							<div class="card-header">
								<h5 class="mb-0">Leads Overview</h5>
							</div>
							<div class="card-body p-3">
								<div style="height: 350px;">
									<canvas id="leadsChart" style="width: 100%; height: 100% !important;"></canvas>
								</div>
							</div>
						</div>
					</div>
      </div>

    </div>
  </div>


<!-- Customer Section -->
<div class="card">
  <!-- <div class="card-header bg-primary text-white">
    <h5 class="mb-0">Customer</h5>
  </div> -->
  <div class="card-body">

    <!-- 1. Summary Cards (single row, 4 cards) -->
		<div class="row text-center mb-1 mt-0">
			<div class="col-md-3 col-sm-6 mb-2">
				<a href="<?= base_url('customers') ?>" class="stat-link" >
				<div class="card text-white bg-secondary">
					<div class="card-body d-flex justify-content-between align-items-center">
						<i class="fa fa-users fa-2x text-white"></i>
						<div class="text-end">
							<h6 class="mb-0">Today’s Customers</h6>
							<strong><?= $cutomers['today'] ?></strong>
						</div>
					</div>
				</div>
				</a>
			</div>

		<div class="col-md-3 col-sm-6 mb-2">
			<a href="<?= base_url('customers') ?>" class="stat-link" >
			<div class="card text-white bg-warning">
				<div class="card-body d-flex justify-content-between align-items-center">
					<div class="me-3">
						<i class="fa fa-calendar fa-2x text-white"></i>
					</div>
					<div>
						<h6 class="mb-0">This Month</h6>
						<strong><?= $cutomers['month'] ?></strong>
					</div>
				</div>
			</div>
			</a>
		</div>

		<div class="col-md-3 col-sm-6 mb-2">
			<a href="<?= base_url('customers') ?>" class="stat-link" >
			<div class="card text-white bg-success">
				<div class="card-body d-flex justify-content-between align-items-center">
					<div class="me-3">
						<i class="fa fa-calendar fa-2x text-white"></i>
					</div>
					<div>
						<h6 class="mb-0">This Year</h6>
						<strong><?= $cutomers['year'] ?></strong>
					</div>
				</div>
			</div>
			</a>
		</div>

		
  <div class="col-md-3 col-sm-6 mb-2">
		<a href="<?= base_url('customers') ?>" class="stat-link" >
    <div class="card text-white bg-info">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div class="me-3">
          <i class="fa fa-users fa-2x text-white"></i>
        </div>
        <div>
          <h6 class="mb-0">Active Customers</h6>
          <strong><?= $cutomers['active'] ?></strong>
        </div>
      </div>
    </div>
		</a>
  </div>


</div>

<!-- 2. Table and Chart -->
<div class="row">
  
  <!-- Support Ticket Overview -->
  <div class="col-md-4 mb-3">
    <div class="card h-100 position-relative">
      <div class="card-header" style="padding-bottom: 0.3rem;">
        <h5 class="mb-0">Support Ticket Overview</h5>
      </div>
      <div class="card-body position-relative" style="padding-top: 0.5rem; height: 350px;">
        <canvas id="ticketChart" style="width: 100%; height: 100% !important;"></canvas>

        <!-- Custom legend inside the chart area -->
        <div style="
          position: absolute;
          bottom: 10px;
          left: 50%;
          transform: translateX(-50%);
          display: flex;
          flex-direction: column;
          align-items: flex-start;
          font-size: 14px;
          line-height: 1.4;
          width: 160px;
          text-align: left;
        ">
          <?php
						$colors = ['#4285F4', '#9C27B0', '#00C49F'];
						foreach ($ticket_data['labels'] as $i => $label): ?>
							<div>
								<span style="color: <?= $colors[$i] ?>; font-weight: bold;">■</span>
								<?= $label . ' ' . $ticket_data['percentages'][$i] ?>%
							</div>
					<?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Customers Table -->
  <div class="col-md-4 mb-3">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Recent Customers</h4>
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
              <p><span class="float-right"> 
								<a href="<?= base_url('customers/create') ?>" class="btn btn-primary btn-sm rounded">Add</a>
								<a href="<?= base_url('customers') ?>" class="btn btn-success btn-sm rounded">Manage</a>
								</span>
							</p>
        </div>
      </div>
      <div class="card-body" style="overflow-y: auto;">
        <table class="table table-hover table-striped table-bordered zero-configuration dataTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Payment</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($cutomers['customer_list'])) : ?>
              <?php $i = 1; foreach ($cutomers['customer_list'] as $cust) : ?>
                <tr>
                  <td><?= $i++; ?></td>
                  <td>
									<a href="<?php echo base_url(); ?>customers/view?id=<?=$cust->customer_id?>">
										<?= htmlspecialchars($cust->name); ?>
									</a>
									</td>

                  
                  <td>
										<?= number_format($cust->balance, 2); ?> 
									</td>
                  <td><?= htmlspecialchars($cust->status); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
								<td colspan="4" class="text-center">No customer found.</td>
							</tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Customer Activity Chart -->
  <div class="col-md-4 mb-3">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0">Customer Activity</h5>
      </div>
      <div class="card-body p-3" style="height: 350px;">
        <canvas id="customerChart" style="width: 100%; height: 100% !important;"></canvas>
      </div>
    </div>
  </div>

</div> <!-- end row -->


  </div>
</div>


<!-- Supplier Section -->

<div class="card">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Supplier</h5>
    </div>
    <div class="card-body">
      <div class="row d-flex align-items-stretch mt-1">

			  <!-- Supplier Chart -->
			<div class="col-md-6 d-flex">
			<div class="card flex-fill">
				<div class="card-header">
					<h5 class="mb-0">Supplier Overview</h5>
				</div>
				<div class="card-body p-3">
					<div style="position: relative; height: 300px; width: 100%;">
						<canvas id="supplierChart"></canvas>
					</div>
				</div>
			</div>
		</div>


					<!-- Summary Cards -->
					<!-- <div class="col-md-3 d-flex">
						<div class="d-flex flex-column w-100 gap-3">
							<div class="card shadow-sm border flex-fill">
								<div class="card-body d-flex align-items-center">
									<div class="summary-icon bg-primary"><i class="fa fa-file-text-o"></i></div>
									<div>
										<h6 class="mb-0">Today's Leads</h6>
										<strong>100</strong>
									</div>
								</div>
							</div>
							<div class="card shadow-sm border flex-fill">
								<div class="card-body d-flex align-items-center">
									<div class="summary-icon bg-warning"><i class="fa fa-shopping-basket"></i></div>
									<div>
										<h6 class="mb-0">Week Leads</h6>
										<strong>150</strong>
									</div>
								</div>
							</div>
							<div class="card shadow-sm border flex-fill">
								<div class="card-body d-flex align-items-center">
									<div class="summary-icon bg-danger"><i class="fa fa-calendar"></i></div>
									<div>
										<h6 class="mb-0">This Month Leads</h6>
										<strong>100</strong>
									</div>
								</div>
							</div>
							<div class="card shadow-sm border flex-fill">
								<div class="card-body d-flex align-items-center">
									<div class="summary-icon bg-success"><i class="fa fa-calendar"></i></div>
									<div>
										<h6 class="mb-0">This Year Leads</h6>
										<strong>100</strong>
									</div>
								</div>
							</div>
						</div>
					</div> -->

					<!-- Recent Leads Table -->
					<div class="col-md-6 ">
						<div class="card ">
							<div class="card-header">
								<h4 class="card-title">Recent Suppliers</h4>
								<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <p>
											  <span class="float-right"> 
												<a href="<?= base_url('supplier/create') ?>" class="btn btn-primary btn-sm rounded ">Add</a>
												<a href="<?= base_url('supplier') ?>" class="btn btn-success btn-sm rounded">Manage</a>
												</span>
										</p>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-hover table-striped table-bordered zero-configuration dataTable">
									<thead>
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Email</th>
											<th>Phone</th>
										</tr>
									</thead>
									<tbody>
										<?php if (!empty($suppliers['supplier_list'])) : ?>
											<?php $i = 1;
											foreach ($suppliers['supplier_list'] as $sup) : ?>
												<tr>
													<td><?= $i++; ?></td>
													<td>
														<a href="<?php echo base_url(); ?>supplier/view?id=<?=$sup->supplier_id?>">
														<?= $sup->name ?>
														</a>
													</td>
													<td><?= $sup->email ?></td>
													<td><?= $sup->phone ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else : ?>
											<tr>
												<td colspan="4" class="text-center">No supplier found.</td>
											</tr>											
										<?php endif; ?>
										 
									</tbody>
								</table>
							</div>
						</div>
					</div>

					
      </div>

    </div>
  </div>

		
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ApexCharts CDN (not used but included) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  const leadData = <?= json_encode($lead_graph); ?>;
</script>



<script>
  const ctx = document.getElementById('leadsChart').getContext('2d');

  const labels = ['Today', 'Week', 'Month', 'Quarter', 'Year'];

  const draftData = [
    leadData.daily_draft_count || 0,
    leadData.weekly_draft_count || 0,
    leadData.monthly_draft_count || 0,
    leadData.quarterly_draft_count || 0,
    leadData.yearly_draft_count || 0
  ];

  const createdData = [
    leadData.daily_assigned_count || 0,
    leadData.weekly_assigned_count || 0,
    leadData.monthly_assigned_count || 0,
    leadData.quarterly_assigned_count || 0,
    leadData.yearly_assigned_count || 0
  ];

  const convertedData = [
    leadData.daily_closed_count || 0,
    leadData.weekly_closed_count || 0,
    leadData.monthly_closed_count || 0,
    leadData.quarterly_closed_count || 0,
    leadData.yearly_closed_count || 0
  ];

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Draft',
          data: draftData,
          backgroundColor: '#2962FF',
          borderRadius: 6,
          barPercentage: 0.4,
          categoryPercentage: 0.6
        },
        {
          label: 'Created',
          data: createdData,
          backgroundColor: '#00C9FF',
          borderRadius: 6,
          barPercentage: 0.4,
          categoryPercentage: 0.6
        },
        {
          label: 'Converted to Quote',
          data: convertedData,
          backgroundColor: '#FF6F61',
          borderRadius: 6,
          barPercentage: 0.4,
          categoryPercentage: 0.6
        }
      ]
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
          grid: {
            display: false
          }
        },
        y: {
          beginAtZero: true,
          suggestedMax: 100,
          ticks: {
            stepSize: 10
          }
        }
      }
    }
  });
</script>


<script>
  const monthlyCustomerData = <?= json_encode($cutomers['monthly_data']) ?>;

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

  //console.log("Customer Data:", monthlyCustomerData);

  const ctxx = document.getElementById('customerChart').getContext('2d');

  new Chart(ctxx, {
    type: 'bar',
    data: {
      labels: getLast12MonthsLabels(),
      datasets: [{
        label: 'Customers',
        data: monthlyCustomerData,
        backgroundColor: '#2962FF',
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
	 const monthlySupplierData = <?= json_encode($suppliers['monthly_data']) ?>;
  // Generate last 12 month labels with month and year
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

  const sup = document.getElementById('supplierChart').getContext('2d');
  new Chart(sup, {
    type: 'bar',
    data: {
      labels: getLast12MonthsLabels(), // Month + Year
      datasets: [
        {
          label: 'Suppliers',
          data: monthlySupplierData, // 12 values
          backgroundColor: '#4CAF50' // Single green color
        }
      ]
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
  const tkt = document.getElementById('ticketChart').getContext('2d');

  const dataValues = <?= json_encode($ticket_data['values']) ?>;
  const total = <?= $ticket_data['total'] ?>;

  new Chart(tkt, {
    type: 'doughnut',
    data: {
      labels: ['Waiting', 'Processing', 'Solved'],
      datasets: [{
        data: dataValues,
        backgroundColor: ['#4285F4', '#9C27B0', '#00C49F'],
        borderWidth: 0,
        borderRadius: 15,
        cutout: '75%'
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

        const text = 'Total Tickets';
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







