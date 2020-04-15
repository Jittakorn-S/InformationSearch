// Call the dataTables jQuery plugin
$(document).ready(function () {
  $('#dataTable').dataTable({
    "order": [
      [1, "desc"]
    ]
  });
});