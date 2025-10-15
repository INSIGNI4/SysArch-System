

document.addEventListener("DOMContentLoaded", function() {
  const orderedInput = document.getElementById("update-orderedQuantity");
  const receivedInput = document.getElementById("update-received");
  const issueInput = document.getElementById("update-issue");

  if (orderedInput && receivedInput && issueInput) {
    function validateQuantities() {
      const ordered = parseInt(orderedInput.value) || 0;
      const received = parseInt(receivedInput.value) || 0;
      const issue = parseInt(issueInput.value) || 0;
      const total = received + issue;

      // Validation rule: must match exactly
        if (receivedInput.value !== "" && issueInput.value !== "") {
            const total = received + issue;

            if (total !== ordered) {
            alert("Invalid Quantity: Total Received + With Issue must equal Ordered Quantity.");
            receivedInput.value = "";
            issueInput.value = "";
            receivedInput.focus();
            }
        }
    }

    // Trigger validation when user stops typing or leaves field
    receivedInput.addEventListener("change", validateQuantities);
    issueInput.addEventListener("change", validateQuantities);

    // Optional: prevent form submission if invalid
    const form = receivedInput.closest("form");
    if (form) {
      form.addEventListener("submit", function(e) {
        const ordered = parseInt(orderedInput.value) || 0;
        const received = parseInt(receivedInput.value) || 0;
        const issue = parseInt(issueInput.value) || 0;
        const total = received + issue;
        if (total !== ordered) {
          e.preventDefault();
          alert("Please fix the quantities before saving.");
        }
      });
    }
  }
});














// document.addEventListener('DOMContentLoaded', function() {
//   const orderedInput = document.getElementById('Quantity')
//   const receivedInput = document.getElementById('TotalReceived')
//   const issueInput = document.getElementById('withIssue')



//   if (orderedInput && receivedInput && issueInput) {
//     function validateQuantities() {
//       const ordered = parseInt(orderedInput.value) || 0;
//       const received = parseInt(receivedInput.value) || 0;
//       const issue = parseInt(issueInput.value) || 0;
//       const total = received + issue;

//       if (total > ordered || total < ordered) {
//         alert('Invalid Quantity: The sum of Total Received and With Issue must equal Ordered Quantity.');
//         receivedInput.value = '';
//         issueInput.value = '';
//       }
//     }

//     receivedInput.addEventListener('input', validateQuantities);
//     issueInput.addEventListener('input', validateQuantities);
//   }
// });









// const quantityInput = document.getElementById('Quantity');
// const receivedInput = document.getElementById('TotalReceived');
// const issueInput = document.getElementById('withIssue');

// // Run check whenever any relevant input changes
// quantityInput.addEventListener('input', validateInputs);
// receivedInput.addEventListener('input', validateInputs);
// issueInput.addEventListener('input', validateInputs);







//   const received = parseFloat(receivedInput.value) || 0;
//   const issue = parseFloat(issueInput.value) || 0;
//   const total = received + issue;
//   const quantity = parseInt(quantityInput.value);


//   if (total < quantity) {
//     alert('Invalid Quantity.');
//     received.value = '';
//     issue.value = '';
//     return;
//   }

//   if (quantity > total) {
//     alert('Quantity cannot exceed Total Received.');
//     received.value = '';
//     issue.value = '';
//     return;
//   }

