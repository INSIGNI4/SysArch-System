<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dynamic Select2 Inputs with Colspan</title>

  <!-- Include jQuery and Select2 CSS/JS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    td, th {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
    button {
      margin-right: 10px;
    }
  </style>
</head>
<body>

  <h3>Dynamic Select2 Inputs with Colspan Adjustment</h3>

  <!-- Table for displaying Select2 inputs -->
  <table>
    <thead>
      <tr>
        <th>Actions</th>
        <th>Select Inputs</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td rowspan="1" id="action-cell">
          <!-- Action buttons -->
          <button id="addSelectButton">+</button>
          <button id="removeSelectButton">-</button>
        </td>
        <td id="select-cell" colspan="1">
          <select class="select2">
            <option value="option1">Option 1</option>
            <option value="option2">Option 2</option>
            <option value="option3">Option 3</option>
          </select>
        </td>
      </tr>
    </tbody>
  </table>

  <script>
    // Initialize the first select2 input
    $(document).ready(function() {
      $(".select2").select2();
    });

    // When the '+' button is clicked, add a new select2 input
    $("#addSelectButton").click(function() {
      // Create a new select element with the same options as the first one
      const newSelect = $("<select class='select2'><option value='option1'>Option 1</option><option value='option2'>Option 2</option><option value='option3'>Option 3</option></select>");
      
      // Append the new select element to the cell
      $("#select-cell").append(newSelect);

      // Re-initialize Select2 for the new select input
      newSelect.select2();

      // Update colspan based on the number of select2 inputs
      updateColspan();
    });

    // When the '-' button is clicked, remove the last select2 input
    $("#removeSelectButton").click(function() {
      const selectElements = $(".select2"); // Get all select2 elements
      if (selectElements.length > 1) { // Make sure there's more than 1 select
        selectElements.last().remove(); // Remove the last select2 input
        updateColspan();
      }
    });

    // Function to update the colspan of the select-cell
    function updateColspan() {
      const numSelects = $(".select2").length; // Count the number of select2 inputs
      $("#select-cell").attr("colspan", numSelects); // Update colspan dynamically
    }
  </script>

</body>
</html>
