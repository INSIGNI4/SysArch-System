// === PRODUCT INFO INPUTS ===
const productSelect = document.getElementById('Product_IDSALES');
const productNameInput = document.getElementById('ProductName');
const barcodeInput = document.getElementById('Barcode');
const unitPriceInput = document.getElementById('Unit_Price');
const quantityInput = document.getElementById('Quantity');
const totalPriceInput = document.getElementById('TotalPrice');
const totalStock = document.getElementById('Current_Stock'); // <-- single declaration only!
const batchSelect = document.getElementById('Product_IDSALES_Batch');

// === PRODUCT CHANGE HANDLER ===
productSelect.addEventListener('change', () => {
  const productId = productSelect.value;
  if (!productId) return;

  // Fetch product info
  fetch(`get_product_details.php?Product_ID=${productId}`)
    .then(res => res.json())
    .then(data => {
      console.log('Product info:', data);
      if (data.store_price) {
        unitPriceInput.value = data.store_price;
        productNameInput.value = data.product_name;
        barcodeInput.value = data.barcode;
        calculateStock(data); // show overall stock initially
        calculateTotal();     // recalc price if quantity already filled
      } else {
        unitPriceInput.value = '';
        productNameInput.value = '';
        barcodeInput.value = '';
        totalPriceInput.value = '';
        totalStock.value = '';
        alert('Product not found or no price available.');
      }
    })
    .catch(err => console.error('Fetch error:', err));

  // Fetch batch info for this product
  fetch(`get_batches.php?Product_ID=${productId}`)
    .then(res => res.json())
    .then(batches => {
      batchSelect.innerHTML = ''; // clear old options
      if (Array.isArray(batches) && batches.length > 0) {
        batchSelect.innerHTML = '<option disabled selected>Select Batch</option>';
        batches.forEach(batch => {
          const option = document.createElement('option');
          option.value = batch.BatchNum;
          option.textContent = `${batch.BatchNum} — Exp: ${batch.ExpirationDate}`;
          option.dataset.quantity = batch.Quantity;
          batchSelect.appendChild(option);
        });
      } else {
        batchSelect.innerHTML = '<option disabled selected>No available batches</option>';
        totalStock.value = '';
      }
    })
    .catch(err => console.error('Batch fetch error:', err));
});

// === WHEN BATCH IS SELECTED ===
batchSelect.addEventListener('change', () => {
  const selectedOption = batchSelect.options[batchSelect.selectedIndex];
  const batchQty = parseInt(selectedOption.dataset.quantity || 0);
  const LabelStock = document.getElementById('Current_Stock_label');

  // Override current stock to batch quantity
  totalStock.value = batchQty;
  quantityInput.max = batchQty;

  if (batchQty < 1) {
    totalStock.style.color = "red";
    LabelStock.style.color = "red";
  } else {
    totalStock.style.color = "green";
    LabelStock.style.color = "green";
  }
});

// === TOTAL PRICE CALCULATION ===
quantityInput.addEventListener('input', calculateTotal);

function calculateTotal() {
  const unitPrice = parseFloat(unitPriceInput.value);
  const quantity = parseInt(quantityInput.value);
  const currentStock = parseInt(totalStock.value);

  if (isNaN(unitPrice) || isNaN(quantity)) {
    totalPriceInput.value = '';
    return;
  }

  if (quantity < 1) {
    alert('Invalid Quantity.');
    quantityInput.value = '';
    totalPriceInput.value = '';
    return;
  }

  if (quantity > currentStock) {
    alert('Quantity cannot exceed current stock.');
    quantityInput.value = '';
    totalPriceInput.value = '';
    return;
  }

  totalPriceInput.value = (unitPrice * quantity).toFixed(2);
}

// === PRODUCT STOCK DISPLAY ===
function calculateStock(data) {
  const unitsOrdered = parseInt(data.units_ordered || 0);
  const unitsSold = parseInt(data.units_sold || 0);
  const CurrentStock = unitsOrdered - unitsSold;
  const LabelStock = document.getElementById('Current_Stock_label');

  totalStock.value = CurrentStock;
  quantityInput.max = CurrentStock;

  if (CurrentStock < 1) {
    totalStock.style.color = "red";
    LabelStock.style.color = "red";
  } else {
    totalStock.style.color = "green";
    LabelStock.style.color = "green";
  }
}









// const productSelect = document.getElementById('Product_IDSALES');
// const productNameInput = document.getElementById('ProductName');
// const barcodeInput = document.getElementById('Barcode');
// const unitPriceInput = document.getElementById('Unit_Price');
// const quantityInput = document.getElementById('Quantity');
// const totalPriceInput = document.getElementById('TotalPrice');


// const totalStock = document.getElementById('Current_Stock'); // FOR STOCK LEVEL








// productSelect.addEventListener('change', () => {
//   const productId = productSelect.value;

//   if (!productId) return;

//   // ✔ Match GET param to PHP
//   fetch(`get_product_details.php?Product_ID=${productId}`)
  
//     .then(res => res.json())
//     .then(data => {
//       console.log('Product info:', data);
//       if (data.store_price) {
//         unitPriceInput.value = data.store_price;
//         productNameInput.value = data.product_name;
//         barcodeInput.value = data.barcode;
//         calculateStock(data); // FOR STOCK LEVEL
//         calculateTotal(); // recalculate if quantity already input
//       } else {
//         unitPriceInput.value = '';
//         productNameInput.value = '';
//         barcodeInput.value = '';
//         totalPriceInput.value = '';
//         totalStock.value = '';// FOR STOCK LEVEL
//         alert('Product not found or no price available.');
//       }
//     })
//     .catch(err => {
//       console.error('Fetch error:', err);
//     });
// });

// // for total price
// quantityInput.addEventListener('input', calculateTotal);

// function calculateTotal() {
//   const unitPrice = parseFloat(unitPriceInput.value);
//   const quantity = parseInt(quantityInput.value);
  
//   const currentStock = parseInt(totalStock.value);//for total stock

//   if (quantity > currentStock) {//for total stock 
//     alert('Quantity cannot exceed current stock.');
//     quantityInput.value = '';
//     totalPriceInput.value = '';
//     return;
//   }

//     if (quantity < 1) {//for 0 error 
//     alert('Invalid Quantity.');
//     quantityInput.value = '';
//     totalPriceInput.value = '';
//     return;
//   }






//   if (!isNaN(unitPrice) && !isNaN(quantity)) {
//     totalPriceInput.value = (unitPrice * quantity).toFixed(2);
//   } else {
//     totalPriceInput.value = '';
//   }
// }

// function calculateStock(data) {
//   const unitsOrdered = parseInt(data.units_ordered || 0);
//   const unitsSold = parseInt(data.units_sold || 0);

//   // const quantity = parseInt(quantityInput.value);
//   // // const unitsOrdered = parseInt(data.units_ordered);
//   // // const unitsSold = parseInt(data.units_sold);

//   // if (quantity > currentStock) {
//   //   alert('Quantity cannot exceed current stock.');
//   //   quantityInput.value = '';
//   //   totalPriceInput.value = '';
//   //   return;
//   // }

//   if (!isNaN(unitsOrdered) && !isNaN(unitsSold)) {
//     // totalStock.value = Number((unitsOrdered - unitsSold).toFixed(2));
//     const CurrentStock = unitsOrdered - unitsSold;
//     const LabelStock = document.getElementById('Current_Stock_label')
//     totalStock.value = CurrentStock;
//     quantityInput.max = CurrentStock;
//     if (CurrentStock < 1) {
//       totalStock.style.color = "red";
//       LabelStock.style.color = "red";
//     }
//     else{
//       totalStock.style.color = "green";
//       LabelStock.style.color = "green";
//     }
//   } else {
//     totalStock.value = '';
//     quantityInput.removeAttribute('max');
//   }
// }



// const batchSelect = document.getElementById('Product_IDSALES_Batch');
// const totalStock = document.getElementById('Current_Stock');

// // when selecting product, also fetch its batches
// productSelect.addEventListener('change', () => {
//   const productId = productSelect.value;

//   if (!productId) return;

//   // get batch list for selected product
//   fetch(`get_batches.php?Product_ID=${productId}`)
//     .then(res => res.json())
//     .then(batches => {
//       batchSelect.innerHTML = ''; // clear existing options
//       if (Array.isArray(batches) && batches.length > 0) {
//         batchSelect.innerHTML = '<option disabled selected>Select Batch</option>';
//         batches.forEach(batch => {
//           const option = document.createElement('option');
//           option.value = batch.BatchNum;
//           option.textContent = `${batch.BatchNum} — Exp: ${batch.ExpirationDate}`;
//           option.dataset.quantity = batch.Quantity; // store quantity
//           batchSelect.appendChild(option);
//         });
//       } else {
//         batchSelect.innerHTML = '<option disabled selected>No available batches</option>';
//         totalStock.value = '';
//       }
//     })
//     .catch(err => console.error('Batch fetch error:', err));
// });


// batchSelect.addEventListener('change', () => {
//   const selectedOption = batchSelect.options[batchSelect.selectedIndex];
//   const batchQty = selectedOption.dataset.quantity;

//   if (batchQty !== undefined) {
//     totalStock.value = batchQty;
//     quantityInput.max = batchQty;

//     const LabelStock = document.getElementById('Current_Stock_label');
//     if (batchQty < 1) {
//       totalStock.style.color = "red";
//       LabelStock.style.color = "red";
//     } else {
//       totalStock.style.color = "green";
//       LabelStock.style.color = "green";
//     }
//   }
// });





//BATCHHH












// FOR STOCK LEVEL 

// function calculateStock() {
//   const orderedInput = null;
//   const soldInput = null;

//   orderedInput.value = data.units_ordered;
//   soldInput.value = data.units_sold;

//   const unitsOrdered = parseInt(orderedInput.value);
//   const unitSold = parseInt(soldInput.value);

//   if (!isNaN(unitsOrdered) && !isNaN(unitSold)) {
//     totalStock.value = (unitsOrdered - unitSold).toFixed(2);
//   } else {
//     totalStock.value = '';
//   }
// }
// END FOR STOCK LEVEL 












//   const productSelect = document.getElementById('Product_ID');
//   const unitPriceInput = document.getElementById('Unit_Price');
//   const quantityInput = document.getElementById('Quantity');
//   const totalPriceInput = document.getElementById('TotalPrice');

//   productSelect.addEventListener('change', () => {
//     const productId = productSelect.value;

//     if (!productId) return;

//     fetch(`get_product_price.php?Product_ID=${productId}`)
//       .then(res => res.json())
//       .then(data => {
//         if (data.store_price) {
//           unitPriceInput.value = data.store_price;
//           calculateTotal(); // recalculate if quantity already input
//         } else {
//           unitPriceInput.value = '';
//           totalPriceInput.value = '';
//           alert('Product not found or no price available.');
//         }
//       });
//   });

//   quantityInput.addEventListener('input', calculateTotal);

//   function calculateTotal() {
//     const unitPrice = parseFloat(unitPriceInput.value);
//     const quantity = parseInt(quantityInput.value);

//     if (!isNaN(unitPrice) && !isNaN(quantity)) {
//       totalPriceInput.value = (unitPrice * quantity).toFixed(2);
//     } else {
//       totalPriceInput.value = '';
//     }
//   }