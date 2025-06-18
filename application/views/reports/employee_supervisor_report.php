
<div id="employee-left-section" class="row">
  <!-- Left Panel: Tree -->
 <div class="col-lg-3 col-md-5 col-sm-12 col-12">
    <div id="tree-container">
        <h5><b>Supervisors</b></h5><hr>
        <div id="tree"></div>
    </div>
 </div>

  <!-- Right Panel: Item Details -->
  <div class="col-lg-9 col-md-7 col-sm-12 col-12">
    <div id="details-container">
        <div class="card h-100">
        <div class="card-header">
            <h5 id="employee-title" class="mb-0">Employees Details</h5><hr>
        </div>
            <div class="card-body" id="details-content"></div>
        </div>
    </div>
  </div>
</div>

<script>


$('#tree').jstree({
  'core': {
    'themes': {
      'icons': true
    },
    'data': {
      'url': function (node) {
        return node.id === '#' ? '<?= base_url("reports/get_supervisors") ?>' : '<?= base_url("reports/get_employees") ?>';
      },
      'data': function (node) {
        return { 'id': node.id };
      }
    }
  },
  'plugins': ["wholerow"] 
});
$('#tree').on("select_node.jstree", function (e, data) {
    const node = data.node;
    const nodeId = node.id;

    let actualId = nodeId.replace('sup_', '').replace('emp_', '');

    if (node.id.startsWith('sup_')) {
        $('#employee-title').text('Supervisor Details');
    }else{
        $('#employee-title').text('Employee Details');
    }

    $.ajax({
        url: '<?= base_url("reports/get_profile") ?>',
        method: 'GET',
        data: { id: actualId },
        success: function (res) {
            $('#details-content').html(res);
        },
        error: function () {
            $('#details-content').html('<p class="text-danger">Error loading profile details.</p>');
        }
    });
});

</script>