const productSelect = document.getElementById('Product_ID');
const productNameInput = document.getElementById('ProductName');
const barcodeInput = document.getElementById('Barcode');
const unitPriceInput = document.getElementById('Unit_Price');
const quantityInput = document.getElementById('Quantity');
const totalPriceInput = document.getElementById('TotalPrice');

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
        calculateTotal(); // recalculate if quantity already input
      } else {
        unitPriceInput.value = '';
        productNameInput.value = '';
        barcodeInput.value = '';
        totalPriceInput.value = '';
        alert('Product not found or no price available.');
      }
    })
    .catch(err => {
      console.error('Fetch error:', err);
    });
});

quantityInput.addEventListener('input', calculateTotal);

function calculateTotal() {
  const unitPrice = parseFloat(unitPriceInput.value);
  const quantity = parseInt(quantityInput.value);

  if (!isNaN(unitPrice) && !isNaN(quantity)) {
    totalPriceInput.value = (unitPrice * quantity).toFixed(2);
  } else {
    totalPriceInput.value = '';
  }
}











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