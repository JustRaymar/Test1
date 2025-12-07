console.log("script.js is loaded correctly");

// Modal Functions
function openAddProductModal() {
  document.getElementById("add-product-modal").style.display = "block";
}

function closeModal() {
  document.querySelectorAll(".modal").forEach((modal) => {
    modal.style.display = "none";
  });
}

/*document.getElementById("edit-product-form").addEventListener("submit", function (e) {
    e.preventDefault();
    const index = document.getElementById("edit-product-id").value;
    const updatedProduct = {
		amount: parseFloat(document.getElementById("edit-product-amount").value),
		name: document.getElementById("edit-product-name").value,
		price: parseFloat(document.getElementById("edit-product-price").value),
		description: document.getElementById("edit-product-description").value,
    };
    editProduct(index, updatedProduct);
    closeModal();
  });*/

// Close modal when clicking outside
window.onclick = function (event) {
  if (event.target.classList.contains("modal")) {
    closeModal();
  }
};

function changeQuantity(amount) {
  const input = document.getElementById("product-amount");
  let currentValue = parseInt(input.value) || 1; // fallback to 1 if empty/NaN
  currentValue += amount;

  // Prevent going below 1
  if (currentValue < 1) currentValue = 1;

  input.value = currentValue;
}

function changeEditQuantity(amount) {
  const input = document.getElementById("edit-product-amount");
  let currentValue = parseInt(input.value) || 1; // fallback to 1 if empty/NaN
  currentValue += amount;

  // Prevent going below 1
  if (currentValue < 1) currentValue = 1;

  input.value = currentValue;
}

document.getElementById("add-product-form").addEventListener("submit", function (event) {
    event.preventDefault();

    let productName = document.getElementById("product-name").value;
    let productPrice = document.getElementById("product-price").value;
    let productDescription = document.getElementById("product-description").value;
	let productAmount = document.getElementById("product-amount").value;

    let formData = new FormData();
    formData.append("product_name", productName);
    formData.append("product_price", productPrice);
    formData.append("product_description", productDescription);
	formData.append("product_amount", productAmount);

    fetch("add_product.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            closeModal();
            loadProducts(); // Refresh product list
        }
    })
    .catch(error => console.error("Error:", error));
});

let products = [];

// Function to Load Products Dynamically
function loadProducts() {
    fetch("getProducts.php")
    .then(response => response.json())
    .then(data => {
        products = data; // Store products globally

        let gridContainer = document.getElementById("product-grid");
        gridContainer.innerHTML = `
            <div class='grid-item add-product-tile' onclick='openAddProductModal()'>
				<div class='plus'>
				<center>
					<img src='plus.png' alt='Add Product' />
					<h2>Add New Product</h2>
				</center>
				</div>
            </div>`; // Reset grid

        products.forEach((product, index) => {
            let productHTML = `
                <div class='grid-item' id="product-${product.ProductID}">
					<img src='modals/${product.ProductName}.png' alt='${product.ProductName}'/>
					<div class='grid-square'>
						<div class="product-controls">
							<button class="edit-btn" data-index="${index}">Edit</button>
							<button class="remove-btn" data-index="${index}">Remove</button>
						</div>
						<h2>${product.ProductName}</h2>
						<p>₱${parseFloat(product.Price).toFixed(2)}</p>
						<p>Available amount: ${product.Quantity}</p>
						<p>${product.ProductDesc}</p>
					</div>
                </div>`;

            gridContainer.innerHTML += productHTML;
        });

        // Attach event listeners
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function () {
                const index = this.getAttribute("data-index");
                openEditModal(index);
            });
        });

        document.querySelectorAll(".remove-btn").forEach(button => {
            button.addEventListener("click", function () {
                const index = this.getAttribute("data-index");
                removeProduct(index);
            });
        });
    })
    .catch(error => console.error("Error loading products:", error));
}


document.addEventListener("DOMContentLoaded", function () {
    let productGrid = document.getElementById("product-grid");
    
    if (!productGrid) {
        console.error("Error: Element with ID 'product-grid' not found.");
        return;
    }

    fetch("getProducts.php")
        .then(response => response.json())
        .then(products => {
            console.log("Fetched products:", products);

            // Clear the grid and add "Add Product" tile first
            productGrid.innerHTML = `
                <div class='grid-item add-product-tile' onclick='openAddProductModal()'>
                    <img src='plus.png' alt='Add Product' />
                    <h2>Add New Product</h2>
                </div>`;

            if (products.length === 0) {
                console.warn("No products found.");
                return;
            }

            products.forEach(product => {
                let productHTML = `
                    <div class='grid-item'>
                        <h2>${product.ProductName}</h2>
                        <p>$${product.Price}</p>
                        <p>${product.ProductDesc}</p>
                    </div>`;
                productGrid.innerHTML += productHTML;
            });
        })
        .catch(error => console.error("Fetch error:", error));
});

function openEditModal(index) {
    if (!products || products.length === 0) {
        console.error("Error: Products array is empty or undefined.");
        return;
    }

    const product = products[index]; // Get product data
    if (!product) {
        console.error(`Error: No product found at index ${index}`);
        return;
    }

    document.getElementById("edit-product-id").value = index;
	document.getElementById("edit-product-amount").value = parseFloat(product.Quantity).toFixed(0);
    document.getElementById("edit-product-name").value = product.ProductName;
    document.getElementById("edit-product-price").value = parseFloat(product.Price).toFixed(2);
    document.getElementById("edit-product-description").value = product.ProductDesc;
    document.getElementById("edit-product-modal").style.display = "block";
}

document.getElementById("edit-product-form").addEventListener("submit", function (e) {
    e.preventDefault();

    const index = document.getElementById("edit-product-id").value;
    const updatedProduct = {
        product_id: products[index].ProductID, // Ensure we send the correct ProductID
		amount: document.getElementById("edit-product-amount").value,
        name: document.getElementById("edit-product-name").value,
        price: parseFloat(document.getElementById("edit-product-price").value),
        description: document.getElementById("edit-product-description").value
    };

    let formData = new FormData();
    formData.append("product_id", updatedProduct.product_id);
	formData.append("product_amount", updatedProduct.amount);
    formData.append("product_name", updatedProduct.name);
    formData.append("product_price", updatedProduct.price);
    formData.append("product_description", updatedProduct.description);

    fetch("edit_product.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            closeModal();
            loadProducts(); // Refresh product list after update
        }
    })
    .catch(error => console.error("Error updating product:", error));
});


function removeProduct(index) {
    if (!confirm("Are you sure you want to remove this product?")) {
        return;
    }

    const product_id = products[index].ProductID; // Get Product ID from the products array

    let formData = new FormData();
    formData.append("product_id", product_id);

    fetch("delete_product.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            loadProducts(); // Refresh product list after deletion
        }
    })
    .catch(error => console.error("Error deleting product:", error));
}