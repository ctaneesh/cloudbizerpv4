
  <style>
 

    #lead-table thead th {
    background-color:rgb(152, 146, 163);
    color: #000;
  }

  </style>

<div id="employee-left-section" class="row">
  <!-- Left Panel: Tree -->
 <div class="col-3">
    <div id="tree-container">
        <h5><b>Leads</b></h5><hr>
        <div id="tree"></div>
    </div>
 </div>

  <!-- Right Panel: Item Details -->
  <div class="col-9">
    <div id="details-container">
        <div class="card h-100">
        <div class="card-header">
            <h5 id="lead-title" class="mb-0">Lead Details</h5><hr>
        </div>
            <div class="card-body" id="details-content"></div>
        </div>
    </div>
  </div>
</div>

<script>
//  Get Leads and Quotes tree view
$(function () {
  $('#tree').jstree({
    'core': {
      'themes': {
        'icons': true
      },
      'data': {
        'url': function (node) {
          return node.id === '#' ? '<?= base_url("reports/get_leads") ?>' : '<?= base_url("reports/get_quote") ?>';
        },
        'data': function (node) {
          return { 'id': node.id };
        }
      }
    },
    'plugins': ["wholerow"] // optional: better row click behavior
  })


});

$('#tree').on("select_node.jstree", function (e, data) {
  const node = data.node;
  const tree = $('#tree').jstree(true);

  const nodeId = node.id;

  if (node.parent === '#') {
    // It's a lead node
    $.ajax({
      url: '<?= base_url("reports/lead_details") ?>',
      method: 'GET',
      data: { lead_number: nodeId },
      success: function (res) {
        $('#lead-title').text('Lead Details - ' + nodeId);
        $('#details-content').html(res);
        $('#items-table').DataTable();
      },
      error: function () {
        $('#details-content').html('<p class="text-danger">Error loading lead details.</p>');
      }
    });
  } else if (nodeId.startsWith('quote_')) {
    const quoteNumber = nodeId.replace('quote_', '');
    const leadId = tree.get_parent(node.id); // Get parent node ID = lead ID
    $.ajax({
      url: '<?= base_url("reports/quote_details") ?>',
      method: 'GET',
      data: { quote_number: quoteNumber },
      success: function (res) {
        // $('#lead-title').text('Quote Details - ' + quoteNumber);
        $('#lead-title').text('Lead Id : ' + leadId + ' - Quote Details : ' + quoteNumber);
        $('#details-content').html(res);
        $('#items-table').DataTable();
      },
      error: function () {
        $('#details-content').html('<p class="text-danger">Error loading quote details.</p>');
      }
    });
  }
});

</script>