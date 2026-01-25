let currentStoreIndex = 0;

const storeSellerMap = {
  "BULLDOGS CORNER": 0,
  "BULLDOGS PUNCHBOWL": 1,
  "BULLDOGS QUICKBITES": 2,
  "HUNGRY BULLDOGS": 3,
};

function setStore(index) {
  currentStoreIndex = index;
  updateCarousel();
}

function updateCarousel() {
  const buttons = document.querySelectorAll(".store-btn-vertical");
  if (!buttons.length) return;

  buttons.forEach((btn, i) => {
    btn.classList.toggle("active", i === currentStoreIndex);

    if (i === currentStoreIndex) {
      const storeName = btn.getAttribute("data-store");
      loadProducts(storeName);
    }
  });
}

function loadProducts(storeName) {
  const gridContainer = document.getElementById("product-grid");
  if (!gridContainer) return;

  const sellerId = storeSellerMap[storeName];
  if (!sellerId) {
    gridContainer.innerHTML = "<p>No products found.</p>";
    return;
  }

  fetch(`getProdSeller.php?seller_id=${sellerId}`)
    .then(res => res.json())
    .then(products => {
      gridContainer.innerHTML = "";

      if (!products.length) {
        gridContainer.innerHTML = "<p>No available products.</p>";
        return;
      }

      products.forEach(product => {
        const productDiv = document.createElement("div");
        productDiv.className = "grid-item";
        productDiv.onclick = () =>
          openModal(
            product.ProductName,
            product.ProductDesc,
            product.ProductID,
            product.Quantity,
            product.Price
          );

        const img = document.createElement("img");
        img.src = `modals/${product.ProductName}.png`;
        img.alt = product.ProductName;
        img.onerror = function () {
          this.src = `https://placehold.co/200x200?text=${encodeURIComponent(
            product.ProductName
          )}`;
        };

        const name = document.createElement("div");
        name.className = "product-name";
        name.innerText = product.ProductName;

        const price = document.createElement("div");
        price.className = "product-price";
        price.innerText = "₱" + product.Price;

        productDiv.append(img, name, price);
        gridContainer.appendChild(productDiv);
      });
    })
    .catch(err => {
      console.error(err);
      gridContainer.innerHTML = "<p>Error loading products.</p>";
    });
}

function openModal(title, description, prodId, items, price) {
  document.getElementById("modal-title").innerText = title;
  document.getElementById("modal-description").innerText = description;
  document.getElementById("modal-prodId").value = prodId;
  document.getElementById("modal-availItems").value = items;
  document.getElementById("modal-showAvail").innerText =
    "Available items: " + items;
  document.getElementById("modal-price").value = price;

  document.getElementById("modal-image").src =
    `modals/${title}_r.png`;

  document.getElementById("modal").style.display = "block";
}

function changeQuantity(amount) {
  const input = document.getElementById("modal-quantity");
  input.value = Math.max(1, (parseInt(input.value) || 1) + amount);
}

function closeModal() {
  document.getElementById("modal").style.display = "none";
}

window.onclick = e => {
  if (e.target === document.getElementById("modal")) closeModal();
};

function addToCart(productId, productName, availItems, qty, price) {
  let cart = JSON.parse(sessionStorage.getItem("cart")) || [];

  if (qty > availItems) {
    alert(`Only ${availItems} items available.`);
    return;
  }

  const existing = cart.find(i => i.id === productId);
  if (existing) existing.quantity += Number(qty);
  else cart.push({ id: productId, name: productName, quantity: Number(qty), price: Number(price) });

  sessionStorage.setItem("cart", JSON.stringify(cart));
  alert(`${qty}x ${productName} added to cart`);
  displayTotalPrice();
}

document.addEventListener("DOMContentLoaded", () => {
  const orderBtn = document.getElementById("order-button");
  if (orderBtn) {
    orderBtn.addEventListener("click", e => {
      e.preventDefault();
      addToCart(
        document.getElementById("modal-prodId").value,
        document.getElementById("modal-title").innerText,
        document.getElementById("modal-availItems").value,
        document.getElementById("modal-quantity").value,
        document.getElementById("modal-price").value
      );
    });
  }

  updateCarousel();
});
