<style>
.card-hover-effect {
    transition: all 0.3s ease-in-out;
}
.card-hover-effect:hover {
    transform: scale(1.03);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.3);
}

  .hover-card {
  background-color: #f9f9f9;
  border-radius: 15px;
  padding: 30px 10px;
  transition: 0.3s ease;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  text-decoration: none;
  color: #333;
  text-align: center;
  border: 2px solid #e0e0e0; /* ✅ Light gray border */
}

  .hover-card .card-icon {
    font-size: 1.8rem;
    background-color: rgba(78, 115, 223, 0.12);
    color: #404e67;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    line-height: 48px;
    display: inline-block;
    transition: 0.3s ease;
  }

  .hover-card h5 {
    margin-top: 10px;
    font-size: 0.95rem;
    font-weight: 500;
    transition: 0.3s ease;
  }

  .hover-card:hover {
  background-color: #404e67;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
  transform: translateY(-5px);
  border-color: #fff; /* ✅ Changes to white border on hover */
}

  .hover-card:hover .card-icon {
    background-color: #fff;
    color: #404e67;
  }

  .hover-card:hover h5 {
    color: #fff;
  }

</style>


  <div class="row g-3 mt-2">

    <div class="col-md-3">
        <a href="<?= base_url('SalesOrders') ?>">
            <div class="card shadow-sm p-2 border border-success bg-success text-white card-hover-effect">
                <div class="card-title">
                    <i class="icon-basket "></i> Sales
                </div>
                <div class="card-stat font-weight-bold"><?= $sales['total'] ?></div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="<?= base_url('quote') ?>">
            <div class="card shadow-sm p-2 border border-secondary bg-secondary text-white card-hover-effect">
                <div class="card-title">
                    <i class="icon-call-out"></i> Quotes
                </div>
                <div class="card-stat font-weight-bold"><?= $quotes['total'] ?></div>
            </div>
        </a>
    </div>

	<div class="col-md-3">
        <a href="<?= base_url('Productrequest') ?>">
            <div class="card shadow-sm p-2 border border-warning bg-warning text-white card-hover-effect">
                <div class="card-title">
                    <i class="ft-sliders"></i> Purchase Request
                </div>
                <div class="card-stat font-weight-bold"><?= $purchase_request['total'] ?></div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="<?= base_url('subscriptions') ?>">
            <div class="card shadow-sm p-2 border border-primary bg-primary text-white card-hover-effect">
                <div class="card-title">
                    <i class="ft-radio"></i> Subscriptions
                </div>
                <div class="card-stat font-weight-bold"><?= $subscriptions['total'] ?></div>
            </div>
        </a>
    </div>    

	

    <!-- Table -->
    <div class="col-md-6">
      <div class="card ">
        <div class="card-header">
          <h4 class="card-title">Recent Sales</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <p>
						<span class="float-right"> 
					       <a href="<?php echo base_url() ?>SalesOrders/salesorder_new?token=3" class="btn btn-primary btn-sm rounded">Add Sales</a>
                           <a  href="<?php echo base_url() ?>SalesOrders" class="btn btn-success btn-sm rounded">Manage Sales</a>
						</span>
                    </p>
                </div>
        </div>
        <div class="card-body">
          <table class="table table-hover table-striped table-bordered zero-configuration dataTable">
            <thead>
              <tr><th>#</th><th>Sales Order</th><th>Due Date</th><th>Amount</th></tr>
            </thead>
            <tbody>
				<?php if (!empty($sales['sales_list'])) : ?>	
				    <?php 
						$i = 1; foreach ($sales['sales_list'] as $sals) : 
					?>
					<tr>
						<td><?= $i++; ?></td>
						<td>
							<a href="<?php echo base_url(); ?>SalesOrders/salesorder_new?id=<?=$sals->id?>&token=3">
								<?=$sals->salesorder_number?>
							</a>
						</td>
						<td><?= date('d-m-Y', strtotime($sals->invoiceduedate)); ?></td>
												
						<td><?= number_format($sals->total, 2); ?></td>
											
					</tr>
				<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="4" class="text-center">No sales found.</td>
					</tr>
				<?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

	<div class="col-md-6">
		<div class="card shadow-sm p-2">
			<h5 class="card-title">Sales Overview</h5>
				<div style="height: 350px;">
				<canvas id="salesChart" ></canvas>
			</div>
		</div>
	</div>

	<div class="col-md-3 mb-3">
    <a href="<?= base_url('Productrequest/add') ?>" class="hover-card d-block p-3 text-center">
      <div class="card-icon"><i class="fa fa-address-book-o"></i></div>
      <h5>New Buy Requests</h5>
    </a>
  </div>

  <div class="col-md-3 mb-3">
    <a href="<?= base_url('quote/deliverynote') ?>" class="hover-card d-block p-3 text-center">
      <div class="card-icon"><i class="fa fa-truck"></i></div>
      <h5>New Delivery Note</h5>
    </a>
  </div>

  <div class="col-md-3 mb-3">
    <a href="<?= base_url('DeliveryNotes') ?>" class="hover-card d-block p-3 text-center">
      <div class="card-icon"><i class="fa fa-clipboard"></i></div>
      <h5>Delivery Notes</h5>
    </a>
  </div>

  <div class="col-md-3 mb-3">
    <a href="<?= base_url('Deliveryreturn') ?>" class="hover-card d-block p-3 text-center">
      <div class="card-icon"><i class="fa fa-undo"></i></div>
      <h5>Delivery Returns</h5>
    </a>
  </div>

	



  <div class="col-md-6">
		<div class="card shadow-sm p-2">
			<h5 class="card-title">Delivery Note Overview</h5>
				<div style="height: 350px;">
				<canvas id="delNoteChart" ></canvas>
			</div>
		</div>
	</div>


	<div class="col-md-6">
      <div class="card ">
        <div class="card-header">
          <h4 class="card-title">Recent Delivery Notes</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <p>
						<span class="float-right"> 
						    <a href="<?php echo base_url() ?>quote/deliverynote" class="btn btn-primary btn-sm rounded">Add Delivery Note</a>
                            <a  href="<?php echo base_url() ?>DeliveryNotes" class="btn btn-success btn-sm rounded">Manage Delivery Notes</a>
						</span>
                    </p>
                </div>
        </div>
        <div class="card-body">
          <table class="table table-hover table-striped table-bordered zero-configuration dataTable">
            <thead>
              <tr><th>#</th><th>Delivery Note</th><th>Due Date</th><th>Amount</th></tr>
            </thead>
            <tbody>
				<?php if (!empty($delivery_notes['delivery_notes_list'])) : ?>	
				    <?php 
										$i = 1; foreach ($delivery_notes['delivery_notes_list'] as $deliverynote) :
											$deliverynotenumber = (!empty($deliverynote->delivery_note_number)) ? $deliverynote->delivery_note_number : ($deliverynote->delevery_note_id+1000);
											 $targeturl = ($deliverynote->status=="Draft") ? base_url("quote/deliverynote?id=$deliverynote->delevery_note_id") : base_url("quote/deliverynote?id=$deliverynote->delevery_note_id") ; 
										?>
										<tr>
											<td><?= $i++; ?></td>
											<td>
											<a href="<?= $targeturl; ?>">
												<?= $deliverynotenumber ?>
											</a>
											</td>
											<td><?= date('d-m-Y', strtotime($deliverynote->deliveryduedate)); ?></td>
											
											<td>
												<?= number_format($deliverynote->total_amount, 2); ?> 
											</td>
											
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

	<div class="col-md-6">
      <div class="card ">
        <div class="card-header">
          <h4 class="card-title">Recent Quotes</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <p>
						<span class="float-right">	
						   <a href="<?php echo base_url() ?>quote/create" class="btn btn-primary btn-sm rounded">Add Quote</a>
                           <a  href="<?php echo base_url() ?>quote" class="btn btn-success btn-sm rounded">Manage Quotes</a>
						</span>
                    </p>
                </div>
        </div>
        <div class="card-body">
          <table class="table table-hover table-striped table-bordered zero-configuration dataTable">
            <thead>
              <tr><th>#</th><th>Quote No.</th><th>Due Date</th><th>Amount</th></tr>
            </thead>
            <tbody>
				<?php if (!empty($quotes['quote_list'])) : ?>	
				    <?php 
										$i = 1; foreach ($quotes['quote_list'] as $quot) :
										?>
										<tr>
											<td><?= $i++; ?></td>
											<td>
											<a href="<?php echo base_url(); ?>quote/create?id=<?=$quot->id?>">
												<?= $quot->quote_number ?>
											</a>
											</td>
											<td><?= date('d-m-Y', strtotime($quot->invoiceduedate)); ?></td>
											
											<td>
												<?= number_format($quot->total, 2); ?> 
											</td>
											
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="4" class="text-center">No quote found.</td>
									</tr>
							<?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>


    <div class="col-md-6">
		<div class="card shadow-sm p-2">
			<h5 class="card-title">Quotes Overview</h5>
				<div style="height: 350px;">
				<canvas id="quotChart" ></canvas>
			</div>
		</div>
    </div>

	<div class="col-md-4">
		<div class="card shadow-sm p-2">
			<h5 class="card-title">Purchase Request Overview</h5>
				<div style="height: 350px;">
				<canvas id="purchasRqstPieChart" ></canvas>
			</div>
		</div>
	</div>

	<div class="col-md-8">
      <div class="card ">
        <div class="card-header">
          <h4 class="card-title">Recent Purchase Requests</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <p>
						<span class="float-right"> 
							<a href="<?php echo base_url() ?>Productrequest/add" class="btn btn-primary btn-sm rounded">Add Purchase Request</a>
                            <a  href="<?php echo base_url() ?>Productrequest" class="btn btn-success btn-sm rounded">Manage Purchase Request</a>
						</span>
                    </p>
                </div>
        </div>
        <div class="card-body">
          <table class="table table-hover table-striped table-bordered zero-configuration dataTable">
            <thead>
              <tr><th>#</th><th>Item</th><th>Quantity</th><th>Status</th></tr>
            </thead>
            <tbody>
				<?php if (!empty($purchase_request['purch_rqst_list'])) : ?>	
				    <?php 
										$i = 1; foreach ($purchase_request['purch_rqst_list'] as $rqst) :
										?>
										<tr>
											<td><?= $i++; ?></td>
											<td>
											<!-- <a href="<?php echo base_url(); ?>invoices/create?id=<?=$invc->id?>">
												<?= $rqst->product_name ?>
											</a> -->
											<?= $rqst->product_name ?>
											</td>
											<td><?= $rqst->requested_qty ?></td>
											
											<td>
												<?= $rqst->requested_status ?> 
											</td>
											
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



  
		

    
	<div class="col-lg-12 common-heading"><h1>REPORT NAVIGATIONS</h1></div>
				<!-- Duplicates of Manual Journals - example, can be looped -->

	<div class="col-md-2 mb-1">
			<a href="<?= base_url('reports/sales_orders_report') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
				<span class="row no-gutters align-items-center">
					<span class="col-auto pr-2">
						<i class="icon-wallet"></i>
					</span>
					<span class="col font-weight-bold">
						Sales Order
					</span>
				</span>
			</a>
	</div>


	<div class="col-md-2 mb-1">
	<a href="<?= base_url('Sales/saleviewstatement') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
		<span class="row no-gutters align-items-center">
		<span class="col-auto pr-2"><i class="icon-wallet"></i></span>
		<span class="col font-weight-bold">Sales Purchase</span>
		</span>
	</a>
	</div>

	<div class="col-md-2 mb-1">
	<a href="<?= base_url('reports_quotes') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
		<span class="row no-gutters align-items-center">
		<span class="col-auto pr-2"><i class="icon-wallet"></i></span>
		<span class="col font-weight-bold">Quote</span>
		</span>
	</a>
	</div>

	<div class="col-md-2 mb-1">
	<a href="<?= base_url('reports/sale_purchase_report') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
		<span class="row no-gutters align-items-center">
		<span class="col-auto pr-2"><i class="icon-wallet"></i></span>
		<span class="col font-weight-bold">Sale Purchase</span>
		</span>
	</a>
	</div>

	<div class="col-md-2 mb-1">
	<a href="<?= base_url('reports/inventory_aging_report') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
		<span class="row no-gutters align-items-center">
		<span class="col-auto pr-2"><i class="icon-wallet"></i></span>
		<span class="col font-weight-bold">Inventory Aging</span>
		</span>
	</a>
	</div>

	

	<!-- <div class="col-md-3 mb-1">
	<a href="<?= base_url('reports/trial_balance') ?>" class="btn btn-outline-primary w-100 text-start shadow-sm">
		<span class="row no-gutters align-items-center">
		<span class="col-auto pr-2"><i class="icon-wallet"></i></span>
		<span class="col font-weight-bold">Trial Balance</span>
		</span>
	</a>
	</div> -->






    
      
    

  </div>




 <!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ApexCharts CDN (not used but included) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  const sllabels = ['Today', 'Week', 'Month', 'Quarter', 'Year'];
  const salesData = <?= json_encode($sales_graph); ?>;

  const draftSaleData = [
    salesData.daily_draft_count,
    salesData.weekly_draft_count,
    salesData.monthly_draft_count,
    salesData.quarterly_draft_count,
    salesData.yearly_draft_count
  ];

  const createdSaleData = [
    salesData.daily_created_count || 0,
    salesData.weekly_created_count || 0,
    salesData.monthly_created_count || 0,
    salesData.quarterly_created_count || 0,
    salesData.yearly_created_count || 0
  ];
  

  const lineChartCtx = document.getElementById('salesChart').getContext('2d');

  new Chart(lineChartCtx, {
    type: 'line',
    data: {
      labels: sllabels,
      datasets: [
        {
          label: 'Draft',
          data: draftSaleData,
          borderColor: '#4e73df',
          backgroundColor: 'rgba(78, 115, 223, 0.2)',
          tension: 0.4,
          fill: true
        },
        {
          label: 'Created',
          data: createdSaleData,
          borderColor: '#1cc88a',
          backgroundColor: 'rgba(28, 200, 138, 0.2)',
          tension: 0.4,
          fill: true
        }
      ]
    },
    options: {
      responsive: true,
      layout: {
        padding: {
          left: 20
        }
      },
      plugins: {
        title: {
          display: true,
          //text: 'Draft vs Created Trends (Line Chart)'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `${context.dataset.label}: ${context.raw} entries`;
            }
          }
        }
      },
      scales: {
        x: {
          offset: true
        },
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>





<script>
  const deliveryNoteData = <?= json_encode($delivry_note_graph); ?>;

  const delnot = document.getElementById('delNoteChart').getContext('2d');

  new Chart(delnot, {
    type: 'bar',
    data: {
      labels: ['Today', 'Week', 'Month', 'Quarter', 'Year'],
      datasets: [
        {
          label: 'Draft',
          data: [
            deliveryNoteData.daily_draft_count,
            deliveryNoteData.weekly_draft_count,
            deliveryNoteData.monthly_draft_count,
            deliveryNoteData.quarterly_draft_count,
            deliveryNoteData.yearly_draft_count
          ],
          backgroundColor: '#3AC1A7',
          borderRadius: 6,
          barPercentage: 0.4,
          categoryPercentage: 0.6
        },
        {
          label: 'Created',
          data: [
            deliveryNoteData.daily_created_count,
            deliveryNoteData.weekly_created_count,
            deliveryNoteData.monthly_created_count,
            deliveryNoteData.quarterly_created_count,
            deliveryNoteData.yearly_created_count
          ],
          backgroundColor: '#FDC13A',
          borderRadius: 6,
          barPercentage: 0.4,
          categoryPercentage: 0.6
        },
        {
          label: 'Work In Progress',
          data: [
            deliveryNoteData.daily_progress_count,
            deliveryNoteData.weekly_progress_count,
            deliveryNoteData.monthly_progress_count,
            deliveryNoteData.quarterly_progress_count,
            deliveryNoteData.yearly_progress_count
          ],
          backgroundColor: '#FF9C6E',
          borderRadius: 6,
          barPercentage: 0.4,
          categoryPercentage: 0.6
        },
        {
          label: 'Assigned',
          data: [
            deliveryNoteData.daily_assigned_count,
            deliveryNoteData.weekly_assigned_count,
            deliveryNoteData.monthly_assigned_count,
            deliveryNoteData.quarterly_assigned_count,
            deliveryNoteData.yearly_assigned_count
          ],
          backgroundColor: '#8FB9A8',
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
          ticks: {
            stepSize: 5
          }
        }
      }
    }
  });
</script>


<script>
  // Common labels
  const xxx = ['Today', 'Week', 'Month', 'Quarter', 'Year'];

  // Pass PHP stdClass object to JavaScript
  const quoteData = <?= json_encode($quote_graph); ?>;

  // Prepare individual datasets from PHP data
  const quoteDraftData = [
    quoteData.daily_draft_count || 0,
    quoteData.weekly_draft_count || 0,
    quoteData.monthly_draft_count || 0,
    quoteData.quarterly_draft_count || 0,
    quoteData.yearly_draft_count || 0
  ];

  const quoteCreatedData = [
    quoteData.daily_created_count || 0,
    quoteData.weekly_created_count || 0,
    quoteData.monthly_created_count || 0,
    quoteData.quarterly_created_count || 0,
    quoteData.yearly_created_count || 0
  ];

  const quoteAssignedData = [
    quoteData.daily_assigned_count || 0,
    quoteData.weekly_assigned_count || 0,
    quoteData.monthly_assigned_count || 0,
    quoteData.quarterly_assigned_count || 0,
    quoteData.yearly_assigned_count || 0
  ];

  const quoteSentData = [
    quoteData.daily_sent_count || 0,
    quoteData.weekly_sent_count || 0,
    quoteData.monthly_sent_count || 0,
    quoteData.quarterly_sent_count || 0,
    quoteData.yearly_sent_count || 0
  ];

  // Chart rendering
  const sals = document.getElementById('quotChart').getContext('2d');
  new Chart(sals, {
    type: 'bar',
    data: {
      labels: xxx,
      datasets: [
        {
          label: 'Draft',
          data: quoteDraftData,
          backgroundColor: '#f6c23e'
        },
        {
          label: 'Created',
          data: quoteCreatedData,
          backgroundColor: '#36b9cc'
        },
        {
          label: 'Assigned',
          data: quoteAssignedData,
          backgroundColor: '#e74a3b'
        },
        {
          label: 'Sent',
          data: quoteSentData,
          backgroundColor: '#1cc88a'
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          //text: 'Progress by Status (Stacked Bar Chart)'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `${context.dataset.label}: ${context.raw} quotes`;
            }
          }
        }
      },
      scales: {
        x: { stacked: true },
        y: {
          stacked: true,
          beginAtZero: true
        }
      }
    }
  });
</script>

<script>
  const pieLabels = ['Today', 'Week', 'Month', 'Quarter', 'Year'];

  const purchaseRqstData = <?= json_encode($purchase_rqst_graph); ?>;

  const pieData = [
    purchaseRqstData.daily_count || 0,
    purchaseRqstData.weekly_count || 0,
    purchaseRqstData.monthly_count || 0,
    purchaseRqstData.quarterly_count || 0,
    purchaseRqstData.yearly_count || 0
  ];

  const pieCtx = document.getElementById('purchasRqstPieChart').getContext('2d');

  new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: pieLabels,
      datasets: [{
        label: 'Purchase Requests',
        data: pieData,
        backgroundColor: [
          '#4e73df',
          '#1cc88a',
          '#36b9cc',
          '#f6c23e',
          '#e74a3b'
        ],
        borderColor: '#fff',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          //text: 'Purchase Request Distribution'
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              const label = context.label || '';
              const value = context.raw;
              return `${label}: ${value} requests`;
            }
          }
        }
      }
    }
  });
</script>























