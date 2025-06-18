

<div id="employee-left-section" class="row">
  <!-- Left Panel: Tree -->
 <div class="col-lg-3 col-md-4 col-sm-12 col-12">
    <div id="tree-container">
        <h5><b>Purchase Orders</b></h5><hr>
        <div id="tree"></div>
    </div>
 </div>

  <!-- Right Panel: Item Details -->
  <div class="col-lg-9 col-md-8 col-sm-12 col-12">
    <div id="details-container">
        <div class="card h-100">
        <div class="card-header">
            <h5 id="item-title" class="mb-0">Item Details</h5>
        </div>
            <div class="card-body" id="details-content"></div>
        </div>
    </div>
  </div>
</div>



<script>
$(function () {
  $('#tree').jstree({
    'core': {
      'themes': {
        'icons': true
      },
      'data': {
        'url': function (node) {
          return node.id === '#' ? '<?= base_url("reports/get_roots") ?>' : '<?= base_url("reports/get_children") ?>';
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

    //  Collapse all POs except the one related to this node
    let topParent = node;
    while (tree.get_parent(topParent) !== '#') {
        topParent = tree.get_parent(topParent);
    }

    // Collapse all other POs (top-level nodes)
    tree.get_json('#', { flat: true }).forEach(function (n) {
        if (n.parent === '#' && n.id !== topParent) {
        tree.close_node(n.id);
        }
    });


  if (node.id.startsWith('items_')) {
      const poId = node.parent;
    // if (poId.startsWith('po_')) {
    //   pid = poId.replace('po_', '');
    // }
    const poNode = tree.get_node(poId);
    const poName = poNode ? poNode.text : 'PO';
   
      $.ajax({
        url: '<?= base_url("reports/item_details") ?>',
        method: 'GET',
        data: { item_id: node.id, po_id: poId, purchase_id: poId },
        success: function (res) {
          $('#item-title').text('Item Details - ' + poName);
          $('#details-content').html(res);
          $('#items-table').DataTable();
        },
        error: function () {
          $('#details-content').html('<p class="text-danger">Error loading item details.</p>');
        }
      });
    } else {
      $('#item-title').text('Item Details');
    }

    // view reciept details
    if (node.id.startsWith('reciept_')) {
      const prId = node.id;
    

    const poNode = tree.get_node(prId);
    // console.log(poNode.original.code);

    const poName = poNode ? poNode.original.code : 'PO';

      $.ajax({
        url: '<?= base_url("reports/reciept_details") ?>',
        method: 'GET',
        data: { purchase_reciept_id: prId },
        success: function (res) {
          $('#item-title').text('Reciept Details - ' + poName);
          $('#details-content').html(res);
          $('#items-table').DataTable();
        },
        error: function () {
          $('#details-content').html('<p class="text-danger">Error loading item details.</p>');
        }
      });
    } else {
      $('#item-title').text('Reciept Details');
    }

       // view reciept expense details
       if (node.id.startsWith('expense_')) {
      const exId = node.id;
    if (exId.startsWith('expense_')) {
      exid = exId.replace('expense_', '');
    }

    const poNode = tree.get_node(exId);
    // console.log(poNode.original.code);

    const poName = poNode ? poNode.original.code : 'PO';

      $.ajax({
        url: '<?= base_url("reports/expense_details") ?>',
        method: 'GET',
        data: { purchase_reciept_id: exid },
        success: function (res) {
          $('#item-title').text('Expense Details - ' + poName);
          $('#details-content').html(res);
          $('#items-table').DataTable();
        },
        error: function () {
          $('#details-content').html('<p class="text-danger">Error loading item details.</p>');
        }
      });
    } else {
      $('#item-title').text('Reciept Details');
    }


  });




  // Make node text toggle expand/collapse on click (not just the icon)
$('#tree').on("click", ".jstree-anchor", function (e) {
  const instance = $.jstree.reference(this);
  const node = instance.get_node(this);
  $('#details-content').html('');
  // If clicked node is a parent (has children or can have children), toggle it
  if (node.children.length > 0 || node.state.loaded === false) {
    instance.toggle_node(node);
  }
});

</script>