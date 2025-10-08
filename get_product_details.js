const productSelect = document.getElementById('Product_IDSALES');
const productNameInput = document.getElementById('ProductName');
const barcodeInput = document.getElementById('Barcode');
const unitPriceInput = document.getElementById('Unit_Price');
const quantityInput = document.getElementById('Quantity');
const totalPriceInput = document.getElementById('TotalPrice');


const totalStock = document.getElementById('Current_Stock'); // FOR STOCK LEVEL








productSelect.addEventListener('change', () => {
  const productId = productSelect.value;

  if (!productId) return;

  // ✔ Match GET param to PHP
  fetch(`get_product_details.php?Product_ID=${productId}`)
  
    .then(res => res.json())
    .then(data => {
      console.log('Product info:', data);
      if (data.store_price) {
        unitPriceInput.value = data.store_price;
        productNameInput.value = data.product_name;
        barcodeInput.value = data.barcode;
        calculateStock(data); // FOR STOCK LEVEL
        calculateTotal(); // recalculate if quantity already input
      } else {
        unitPriceInput.value = '';
        productNameInput.value = '';
        barcodeInput.value = '';
        totalPriceInput.value = '';
        totalStock.value = '';// FOR STOCK LEVEL
        alert('Product not found or no price available.');
      }
    })
    .catch(err => {
      console.error('Fetch error:', err);
    });
});

// for total price
quantityInput.addEventListener('input', calculateTotal);

function calculateTotal() {
  const unitPrice = parseFloat(unitPriceInput.value);
  const quantity = parseInt(quantityInput.value);
  
  const currentStock = parseInt(totalStock.value);//for total stock

  if (quantity > currentStock) {//for total stock 
    alert('Quantity cannot exceed current stock.');
    quantityInput.value = '';
    totalPriceInput.value = '';
    return;
  }

    if (quantity < 1) {//for 0 error 
    alert('Invalid Quantity.');
    quantityInput.value = '';
    totalPriceInput.value = '';
    return;
  }






  if (!isNaN(unitPrice) && !isNaN(quantity)) {
    totalPriceInput.value = (unitPrice * quantity).toFixed(2);
  } else {
    totalPriceInput.value = '';
  }
}

function calculateStock(data) {
  const unitsOrdered = parseInt(data.units_ordered || 0);
  const unitsSold = parseInt(data.units_sold || 0);

  // const quantity = parseInt(quantityInput.value);
  // // const unitsOrdered = parseInt(data.units_ordered);
  // // const unitsSold = parseInt(data.units_sold);

  // if (quantity > currentStock) {
  //   alert('Quantity cannot exceed current stock.');
  //   quantityInput.value = '';
  //   totalPriceInput.value = '';
  //   return;
  // }

  if (!isNaN(unitsOrdered) && !isNaN(unitsSold)) {
    // totalStock.value = Number((unitsOrdered - unitsSold).toFixed(2));
    const CurrentStock = unitsOrdered - unitsSold;
    totalStock.value = CurrentStock;
    quantityInput.max = CurrentStock;
  } else {
    totalStock.value = '';
    quantityInput.removeAttribute('max');
  }
}




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