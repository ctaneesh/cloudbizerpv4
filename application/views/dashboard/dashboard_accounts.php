<!-- Include in your layout if not already -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> -->


  <div class="row g-3 mt-2">

    <div class="col-md-3">
			<a href="<?= base_url('accounts/add') ?>">
				<div class="card shadow-sm p-2 border border-success">
					<div class="card-title text-success">
						<i class="icon-book-open"></i> Accounts
					</div>
					<div class="card-stat font-weight-bold"><?= $accounts['total']?></div><!--Manage Accounts Count-->
				</div>
			</a>
		</div>


    <div class="col-md-3">
			<a href="<?= base_url('invoices') ?>">
				<div class="card shadow-sm p-2 border border-default">
					<div class="card-title text-default">
						<i class="fa fa-file-text-o"></i> Invoices
					</div>
					<div class="card-stat font-weight-bold text-default"><?= $invoices['total']?></div>
				</div>
			</a>
    </div>

    <div class="col-md-3">
			<a href="<?= base_url('bankingtransactions') ?>">
				<div class="card shadow-sm p-2 border border-danger">
					<div class="card-title text-danger">
						<i class="fa fa-bank"></i> Transactions
					</div>
					<div class="card-stat font-weight-bold text-danger"><?= $transactions['total']?></div>
				</div>
			</a>
    </div>

    <div class="col-md-3">
			<a href="<?= base_url('manualjournals') ?>">
				<div class="card shadow-sm p-2 border border-warning">
					<div class="card-title text-warning">
						<i class="fa fa-book"></i> Manual Journals
					</div>
					<div class="card-stat font-weight-bold text-warning"><?= $manualjournals['total']?></div>
				</div>
			</a>
    </div>
		

    <!-- Chart -->
    

	<div class="col-md-4">
      <div class="card shadow-sm p-2">
        <h5 class="card-title">Invoice Overview</h5>
		<div style="height: 350px;">
        <canvas id="invoiceChart" ></canvas>
		</div>
      </div>
    </div>

    <!-- Products Table -->
    <div class="col-md-4">
      <div class="card ">
        <div class="card-header">
          <h4 class="card-title"><?php echo $this->lang->line('recent_invoices') ?></h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <p><span class="float-right"> 
											<a href="<?php echo base_url() ?>invoices/create" class="btn btn-primary btn-sm rounded">Add Invoice</a>
                      <a  href="<?php echo base_url() ?>invoices" class="btn btn-success btn-sm rounded"><?php echo $this->lang->line('Manage Invoices') ?></a>
											</span>
                    </p>
                </div>
        </div>
        <div class="card-body">
          <table class="table table-hover table-striped table-bordered zero-configuration dataTable">
            <thead>
              <tr><th>#</th><th>Invoice No.</th><th>Invoice Date</th><th>Amount</th></tr>
            </thead>
            <tbody>
								<?php if (!empty($invoices['invoices_list'])) : ?>
									<?php 
										$i = 1; foreach ($invoices['invoices_list'] as $invc) :
											$invoicetid = (!empty($invc->invoice_number)) ? $invc->invoice_number :$invc->tid; 
										?>
										<tr>
											<td><?= $i++; ?></td>
											<td>
											<a href="<?php echo base_url(); ?>invoices/create?id=<?=$invc->id?>">
												<?= $invoicetid ?>
											</a>
											</td>
											<td><?= date('d-m-Y', strtotime($invc->invoicedate)); ?></td>
											
											<td>
												<?= number_format($invc->total, 2); ?> 
											</td>
											
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="4" class="text-center">No invoice found.</td>
									</tr>
							<?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

	<div class="col-md-4">
		<div class="card shadow-sm p-2">
			<h5 class="card-title">Credit Note Overview</h5>
				<div style="height: 350px;">
				<canvas id="creditChart" ></canvas>
			</div>
		</div>
	</div>



  <div class="col-md-3">
		<a href="<?= base_url('invoicecreditnotes') ?>">
			<div class="card shadow-sm p-2 border-0 bg-warning text-white text-center">
				<div class="card-title">
					<i class="fa fa-file-text-o fa-2x mb-2"></i>
					<span class="fw-bold">Invoice Credit Note</span>
				</div>
			</div>
		</a>
	</div>


	<div class="col-md-3">
		<a href="<?= base_url('accounts/balancesheet') ?>">
			<div class="card shadow-sm p-2 border-0 bg-primary text-white text-center">
				<div class="card-title">
					<i class="fa fa-book fa-2x mb-2"></i>
					<span class="fw-bold">Balance Sheet</span>
				</div>
			</div>
		</a>
	</div>	


  <div class="col-md-3">
		<a href="<?= base_url('reports/accountstatement') ?>">
			<div class="card shadow-sm p-2 border-0 bg-success text-white text-center" >
				<div class="card-title">
					<i class="fa fa-file fa-2x mb-2"></i>
					<span class="fw-bold">Account Statements</span>
				</div>
			</div>
		</a>
  </div>

  <div class="col-md-3">
		<a href="<?= base_url('coaaccounttypes') ?>">
			<div class="card shadow-sm p-2 border-0 bg-danger text-white text-center" >
				<div class="card-title">
					<i class="fa fa-cogs fa-2x mb-2"></i>
					<span class="fw-bold">Account Types</span>
				</div>
			</div>
		</a>
  </div>

    <!-- Additional Charts and Manual Journals -->
    <div class="col-md-6">
      <div class="card shadow-sm p-3">
        <h5 class="card-title">Transactions Overview</h5>
        <canvas id="transactionsChart" ></canvas>
      </div>
    </div>

	<!-- Products Table -->
    <div class="col-md-6">
      <div class="card ">
        <div class="card-header">
          <h4 class="card-title">Recent Transactions</h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
              <p><span class="float-right"> 
								<!-- <a href="<?php echo base_url() ?>invoices/create" class="btn btn-primary btn-sm rounded">Add Invoice</a> -->
                <a  href="<?php echo base_url() ?>bankingtransactions" class="btn btn-success btn-sm rounded">Manage Transactions</a>
              </p>
            </div>
        </div>
        <div class="card-body">
          <table class="table table-hover table-striped table-bordered zero-configuration dataTable">
            <thead>
              <tr><th>#</th><th>Trasaction No.</th><th>Date</th><th>Amount</th></tr>
            </thead>
            <tbody>
              <?php if (!empty($transactions['transactions_list'])) : ?>
									<?php 
										$i = 1; foreach ($transactions['transactions_list'] as $transcn) :
											$invoicetid = (!empty($transcn->invoice_number)) ? $transcn->invoice_number :$transcn->tid; 
										?>
										<tr>
											<td><?= $i++; ?></td>
											<td>
											<a href="<?php echo base_url(); ?>transactions/banking_transaction?ref=<?=$transcn->trans_ref_number?>">
												<?=$transcn->trans_number?>
											</a>
											</td>
											<td><?= date('d-m-Y', strtotime($transcn->trans_date)); ?></td>
											
											<td>
												<?= number_format($transcn->trans_amount, 2); ?> 
											</td>
											
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="4" class="text-center">No transaction found.</td>
									</tr>
							<?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

	<div class="col-md-4">
		<a href="<?= base_url('paymentgateways/bank_accounts') ?>" class="text-decoration-none">
			<div class="card  p-2 text-primary shadow-sm rounded " style="background-color: #e9f2fb;">
				<div class="card-title text-success">
					<i class="fa fa-bank"></i> Bank Accounts
				</div>
				<div class="card-stat text-success fw-bold"><?= $bank_accounts['total'] ?></div>
			</div>
		</a>
	</div>



	  <div class="col-md-4">
			<a href="<?= base_url('bankingcategory') ?>">
        <div class="card shadow-sm p-2 text-warning" style="background-color: #fff9e6;">
          <div class="card-title text-warning">
            <i class="fa fa-bank"></i> Banking Category
          </div>
          <div class="card-stat fw-bold"><?= $bank_category['total'] ?></div>
        </div>
			</a>
    </div>

	  <!-- <div class="col-md-3">
				<a href="<?= base_url('bankingtransactions') ?>">
					<div class="card shadow-sm p-2 border border-success">
						<div class="card-title text-success">
							<i class="fa fa-bank"></i> Transactions
						</div>
						<div class="card-stat fw-bold">1,250</div>
					</div>
				</a>
    </div> -->

	  <div class="col-md-4">
			<a href="<?= base_url('reconciliations') ?>">
        <div class="card shadow-sm p-2 text-primary" style="background-color: #e9f2fb;">
          <div class="card-title text-primary">
            <i class="fa fa-bank"></i> Reconciliations
          </div>
          <div class="card-stat fw-bold"><?= $recociliations['total'] ?></div>
        </div>
			</a>
    </div>

		<!-- <div class="col-md-6">
      <div class="card ">
        <div class="card-header">
          <h4 class="card-title"><?php echo $this->lang->line('recent_invoices') ?></h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <p><span class="float-right"> 
											<a href="<?php echo base_url() ?>invoices/create" class="btn btn-primary btn-sm rounded">Add Invoice</a>
                      <a  href="<?php echo base_url() ?>invoices" class="btn btn-success btn-sm rounded"><?php echo $this->lang->line('Manage Invoices') ?></a>
                    </p>
                </div>
        </div>
        <div class="card-body">
          <table class="table table-hover table-striped table-bordered zero-configuration dataTable">
            <thead>
              <tr><th>#</th><th>Name</th><th>Price</th><th>Status</th></tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>	 -->

	  <!-- <div class="col-md-6">
      <div class="card shadow-sm p-3">
        <h5 class="card-title">Website Visitors</h5>
				<div style="display: flex; justify-content: center; align-items: center; height: 350px;">
						<canvas id="doughnutChart"></canvas>
				</div>
					</div>
    </div> -->

    
		<div class="col-lg-12 common-heading"><h1>REPORT NAVIGATIONS</h1></div>
				<!-- Duplicates of Manual Journals - example, can be looped -->

		<div class="col-md-3 mb-1">
			<a href="<?= base_url('reports/ar_aging_report') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
				<span class="row no-gutters align-items-center">
					<span class="col-auto pr-2">
						<i class="icon-wallet"></i>
					</span>
					<span class="col font-weight-bold">
						Aged Receivables
					</span>
				</span>
			</a>
		</div>


		<div class="col-md-3 mb-1">
  <a href="<?= base_url('reports/pay_to_supplier_aged_report') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
    <span class="row no-gutters align-items-center">
      <span class="col-auto pr-2"><i class="icon-wallet"></i></span>
      <span class="col font-weight-bold">Aged Payables</span>
    </span>
  </a>
</div>

<div class="col-md-3 mb-1">
  <a href="<?= base_url('reports/balance_sheet_report') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
    <span class="row no-gutters align-items-center">
      <span class="col-auto pr-2"><i class="icon-wallet"></i></span>
      <span class="col font-weight-bold">Balance Sheet</span>
    </span>
  </a>
</div>

<div class="col-md-3 mb-1">
  <a href="<?= base_url('reports/general_ledger') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
    <span class="row no-gutters align-items-center">
      <span class="col-auto pr-2"><i class="icon-wallet"></i></span>
      <span class="col font-weight-bold">General Ledger</span>
    </span>
  </a>
</div>

<div class="col-md-3 mb-1">
  <a href="<?= base_url('reports/journal_entries') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
    <span class="row no-gutters align-items-center">
      <span class="col-auto pr-2"><i class="icon-wallet"></i></span>
      <span class="col font-weight-bold">Journal Entries</span>
    </span>
  </a>
</div>

<div class="col-md-3 mb-1">
  <a href="<?= base_url('reports/cash_flow') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
    <span class="row no-gutters align-items-center">
      <span class="col-auto pr-2"><i class="icon-wallet"></i></span>
      <span class="col font-weight-bold">Cash Flow</span>
    </span>
  </a>
</div>

<div class="col-md-3 mb-1">
  <a href="<?= base_url('reports/profit_and_loss_new') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
    <span class="row no-gutters align-items-center">
      <span class="col-auto pr-2"><i class="icon-wallet"></i></span>
      <span class="col font-weight-bold">Profit &amp; Loss</span>
    </span>
  </a>
</div>

<div class="col-md-3 mb-1">
  <a href="<?= base_url('reports/trial_balance') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
    <span class="row no-gutters align-items-center">
      <span class="col-auto pr-2"><i class="icon-wallet"></i></span>
      <span class="col font-weight-bold">Trial Balance</span>
    </span>
  </a>
</div>






    
      
    

  </div>




 <!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ApexCharts CDN (not used but included) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  const invoiceData = <?= json_encode($invoice_graph); ?>;

  const ctinvc = document.getElementById('invoiceChart').getContext('2d');

  const invclabels = ['Today', 'Week', 'Month', 'Quarter', 'Year'];

  const draftData = [
    invoiceData.daily_draft_count || 0,
    invoiceData.weekly_draft_count || 0,
    invoiceData.monthly_draft_count || 0,
    invoiceData.quarterly_draft_count || 0,
    invoiceData.yearly_draft_count || 0
  ];

  const createdData = [
    invoiceData.daily_created_count || 0,
    invoiceData.weekly_created_count || 0,
    invoiceData.monthly_created_count || 0,
    invoiceData.quarterly_created_count || 0,
    invoiceData.yearly_created_count || 0
  ];

  new Chart(ctinvc, {
    type: 'bar',
    data: {
      labels: invclabels,
      datasets: [
        {
          label: 'Draft',
          data: draftData,
          backgroundColor: '#3AC1A7',
          borderRadius: 6,
          barPercentage: 0.4,
          categoryPercentage: 0.6
        },
        {
          label: 'Created',
          data: createdData,
          backgroundColor: '#FDC13A',
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
          stacked: false,
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
  const creditData = <?= json_encode($credit_graph); ?>;

  const ctx = document.getElementById('creditChart').getContext('2d');

  const labels = ['Today', 'Week', 'Month', 'Quarter', 'Year'];

  const duePaymentData = [
    creditData.daily_created_count || 0,
    creditData.weekly_created_count || 0,
    creditData.monthly_created_count || 0,
    creditData.quarterly_created_count || 0,
    creditData.yearly_created_count || 0
  ];

  const partialPaidData = [
    creditData.daily_partial_count || 0,
    creditData.weekly_partial_count || 0,
    creditData.monthly_partial_count || 0,
    creditData.quarterly_partial_count || 0,
    creditData.yearly_partial_count || 0
  ];

  const paidData = [
    creditData.daily_paid_count || 0,
    creditData.weekly_paid_count || 0,
    creditData.monthly_paid_count || 0,
    creditData.quarterly_paid_count || 0,
    creditData.yearly_paid_count || 0
  ];

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Due Payment',
          data: duePaymentData,
          backgroundColor: '#2962FF',
          borderRadius: 6,
          barPercentage: 0.4,
          categoryPercentage: 0.6
        },
        {
          label: 'Partially paid',
          data: partialPaidData,
          backgroundColor: '#00C9FF',
          borderRadius: 6,
          barPercentage: 0.4,
          categoryPercentage: 0.6
        },
        {
          label: 'Paid',
          data: paidData,
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
          max: 5, // Adjust max based on your highest expected value
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
</script>

<script>
  // JSON data from PHP backend (already in correct order)
  const monthlyCustomerData = <?= json_encode($transactions['monthly_data']) ?>;

  // Function to generate last 12 months including current
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

  const translabels = getLast12MonthsLabels();

  new Chart(document.getElementById("transactionsChart"), {
    type: "line",
    data: {
      labels: translabels,
      datasets: [
        {
          label: "Transactions",
          data: monthlyCustomerData, // Use array as-is
          borderColor: "#fd7e14",
          backgroundColor: "rgba(253,126,20,0.1)",
          fill: true,
          tension: 0.4,
          pointRadius: 4,
          pointBackgroundColor: "#fd7e14"
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: "Month"
          }
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Number of Transactions"
          }
        }
      }
    }
  });
</script>





	<script>
    // Line Chart
    new Chart(document.getElementById("lineChart"), {
      type: "line",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May"],
        datasets: [
          {
            label: "Google ads",
            data: [80, 210, 180, 120, 0],
            borderColor: "#28a745",
            fill: false
          },
          {
            label: "Facebook ads",
            data: [30, 130, 60, 300, 180],
            borderColor: "#fd7e14",
            fill: false
          }
        ]
      },
      options: {
        responsive: true,
        legend: {
          display: true
        }
      }
    });

  </script>
