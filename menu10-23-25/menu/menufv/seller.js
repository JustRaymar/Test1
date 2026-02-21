console.log("seller.js loaded");

let products = [];

// =========================
// MODAL CONTROLS
// =========================
function openAddProductModal() {
  document.getElementById("add-product-modal").style.display = "block";
}

function closeModal() {
  document.querySelectorAll(".modal").forEach(modal => {
    modal.style.display = "none";
  });
}

window.onclick = function (event) {
  if (event.target.classList.contains("modal")) {
    closeModal();
  }
};

// =========================
// QUANTITY CONTROLS
// =========================
function changeQuantity(amount) {
  const input = document.getElementById("product-amount");
  let value = parseInt(input.value) || 1;
  value = Math.max(1, value + amount);
  input.value = value;
}

function changeEditQuantity(amount) {
  const input = document.getElementById("edit-product-amount");
  let value = parseInt(input.value) || 1;
  value = Math.max(1, value + amount);
  input.value = value;
}

// =========================
// LOAD PRODUCTS
// =========================
function loadProducts() {
  const gridContainer = document.getElementById("product-grid");

  if (!gridContainer) {
    console.error("product-grid element not found");
    return;
  }

  fetch("getProducts.php")
    .then(res => res.json())
    .then(data => {
      products = data;

      // Reset grid with Add Product tile
      gridContainer.innerHTML = `
        <div class="grid-item add-product-tile" onclick="openAddProductModal()">
          <img src="plus.png" alt="Add Product">
          <h2>Add New Product</h2>
        </div>
      `;

      if (!Array.isArray(products) || products.length === 0) {
        return;
      }

      products.forEach((product, index) => {
		  console.log("Price value:", product.price, typeof product.price);
        const productHTML = `
          <div class="grid-item" id="product-${product.product_id}">
            <img src="modals/${product.product_name}.png" alt="${product.product_name}">
            <div class="grid-square">
              <div class="product-controls">
                <button class="edit-btn" data-index="${index}">Edit</button>
                <button class="remove-btn" data-index="${index}">Remove</button>
              </div>
              <h2>${product.product_name}</h2>
              <p>₱${Number(product.unit_price).toFixed(2)}</p>
              <p>Available amount: ${product.available_stock}</p>
              <p>${product.description}</p>
            </div>
          </div>
        `;
        gridContainer.innerHTML += productHTML;
      });

      // Attach buttons
      document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", () => {
          openEditModal(btn.dataset.index);
        });
      });

      document.querySelectorAll(".remove-btn").forEach(btn => {
        btn.addEventListener("click", () => {
          removeProduct(btn.dataset.index);
        });
      });
    })
    .catch(err => {
      console.error("Load products error:", err);
    });
}

// =========================
// EDIT PRODUCT
// =========================
function openEditModal(index) {
  const product = products[index];
  if (!product) return;

  document.getElementById("edit-product-id").value = index;
  document.getElementById("edit-product-name").value = product.product_name;
  document.getElementById("edit-product-price").value = product.price;
  document.getElementById("edit-product-amount").value = product.quantity;
  document.getElementById("edit-product-description").value = product.description;

  document.getElementById("edit-product-modal").style.display = "block";
}

document.getElementById("edit-product-form").addEventListener("submit", e => {
  e.preventDefault();

  const index = document.getElementById("edit-product-id").value;
  const product = products[index];

  const formData = new FormData();
  formData.append("product_id", product.product_id);
  formData.append("product_name", document.getElementById("edit-product-name").value);
  formData.append("product_price", document.getElementById("edit-product-price").value);
  formData.append("product_amount", document.getElementById("edit-product-amount").value);
  formData.append("description", document.getElementById("edit-product-description").value);

  fetch("edit_product.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.status === "success") {
        closeModal();
        loadProducts();
      }
    });
});

// =========================
// DELETE PRODUCT
// =========================
function removeProduct(index) {
  if (!confirm("Remove this product?")) return;

  const formData = new FormData();
  formData.append("product_id", products[index].product_id);

  fetch("delete_product.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.status === "success") {
        loadProducts();
      }
    });
}

// =========================
// INIT
// =========================
document.addEventListener("DOMContentLoaded", loadProducts);