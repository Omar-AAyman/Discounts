// Call the dataTables jQuery plugin
$(document).ready(function() {
    $('#dataTable').DataTable({
        "search": false
    });
});

$(document).ready(function() {
    $('#dataTableActivity').DataTable({
        "order": [[ 0, 'desc' ]]
    });
});

$(document).ready(function() {
    $('#dataTableUserInv').DataTable();
});

$(document).ready(function() {
    $('#dataTableAdminUser').DataTable();
});

$(document).ready(function() {
    $('#dataTableAdminZpanel').DataTable();
});

$(document).ready(function() {
    $('#dataTableAdminPanelOrders').DataTable();
});

$(document).ready(function() {
    $('#dataTableAdminPayment').DataTable();
});

$(document).ready(function() {
    $('#dataTableAdminTickets').DataTable();
});

$(document).ready(function() {
    $('#dataTableAdminInvoices').DataTable();
});


