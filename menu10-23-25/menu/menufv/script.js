function openModal(title, description, prodId, items, price) {
	document.getElementById("modal-title").innerText = title;
	document.getElementById("modal-description").innerText = description;
	const inputField = document.getElementById("modal-prodId");
	inputField.value = prodId;
	document.getElementById("modal-showAvail").innerText = "Available items: " + items;
	const inputField2 = document.getElementById("modal-availItems");
	inputField2.value = items;
	
	const inputField3 = document.getElementById("modal-price");
	inputField3.value = price;

	const imageElement = document.getElementById("modal-image");
	const imageFileName = title + ".png";
	imageElement.src = "modals/" + imageFileName;

	console.log("Input field value set to:", inputField.value);
	document.getElementById("modal").style.display = "block";
}

function changeQuantity(amount) {
	const input = document.getElementById("modal-quantity");
	let currentValue = parseInt(input.value) || 1;
	currentValue += amount;
	if (currentValue < 1) currentValue = 1;
	input.value = currentValue;
}

function closeModal() {
	document.getElementById("modal").style.display = "none";
}

window.onclick = function (event) {
	const modal = document.getElementById("modal");
	if (event.target === modal) {
		closeModal();
	}
};

function removeItem(itemId) {
	const item = document.getElementById(itemId);
	if (item) {
		item.remove();
	}
}

// Function to add item to session storage
function addToCart(productId, productName, availItems, productQuantity, productPrice) {
	let cart = JSON.parse(sessionStorage.getItem("cart")) || [];

	if (productQuantity > availItems) {
		alert(`${productQuantity} is past the available amount of ${productName}, which is ${availItems}`);
	} else {
		let cartItem = {
			id: productId,
			name: productName,
			quantity: Number(productQuantity),
			price: Number(productPrice)
		};

		let existingItem = cart.find(item => item.id === productId);
		if (existingItem) {
			existingItem.quantity += Number(productQuantity);
		} else {
			cart.push(cartItem);
		}

		sessionStorage.setItem("cart", JSON.stringify(cart));
		alert(`${productQuantity}x ${productName} added to cart!`);
		displayTotalPrice();
	}
}

function getCart() {
	return JSON.parse(sessionStorage.getItem("cart")) || [];
}

// calculates total price of all cart items
function calculateTotalPrice() {
	let cart = getCart();
	let total = 0;
	cart.forEach(item => {
		total += item.price * item.quantity;
	});
	return total.toFixed(2);
}

// Attach event listener to the order button
document.addEventListener("DOMContentLoaded", function () {
	const orderButton = document.getElementById("order-button");
	if (orderButton) {
		orderButton.addEventListener("click", function (event) {
			event.preventDefault();
			let productId = document.getElementById("modal-prodId").value;
			let productName = document.getElementById("modal-title").innerText;
			let quantity = document.querySelector("input[name='product_quantity']").value || 1;
			let productPrice = document.getElementById("modal-price")?.value || 0; // assume there's a hidden price field
			addToCart(productId, productName, 999, quantity, productPrice); // replace 999 with actual available stock if needed
		});
	}

	displayTotalPrice(); // Update total on load
});



/*function openModal(title, description, prodId, items) {
	document.getElementById("modal-title").innerText = title;
	document.getElementById("modal-description").innerText = description;
	const inputField = document.getElementById("modal-prodId");
    inputField.value = prodId;
	document.getElementById("modal-showAvail").innerText = "Available items: " + items;
	const inputField2 = document.getElementById("modal-availItems");
	inputField2.value = items;
    
	const imageElement = document.getElementById("modal-image");
	const imageFileName = title + ".png";
	imageElement.src = "modals/" + imageFileName;
	
    console.log("Input field value set to:", inputField.value);;
	document.getElementById("modal").style.display = "block";
}

function changeQuantity(amount) {
  const input = document.getElementById("modal-quantity");
  let currentValue = parseInt(input.value) || 1; // fallback to 1 if empty/NaN
  currentValue += amount;

  // Prevent going below 1
  if (currentValue < 1) currentValue = 1;

  input.value = currentValue;
}

function closeModal() {
	document.getElementById("modal").style.display = "none";
}

// Window closes when clicked outside
window.onclick = function (event) {
	const modal = document.getElementById("modal");
	if (event.target === modal) {
		closeModal();
	}
};

function removeItem(itemId) {
	const item = document.getElementById(itemId);
	if (item) {
		item.remove(); // Remove item
	}
}

//console.log("Script loaded!");
//alert("Script loaded!");

// Function to add item to session storage
function addToCart(productId, productName, availItems, productQuantity) {
	let cart = JSON.parse(sessionStorage.getItem("cart")) || [];
	
	if (productQuantity > availItems) {
		alert(`${productQuantity} is past the available amount of ${productName}, which is ${availItems}`);
	} else {
		let cartItem = {
			id: productId,
			name: productName,
			quantity: productQuantity
		};
			
		// Check if the product already exists in the cart
		let existingItem = cart.find(item => item.id === productId);
		if (existingItem) {
			existingItem.quantity += productQuantity; // Update quantity
		} else {
			cart.push(cartItem); // Add new item
		}
		
		sessionStorage.setItem("cart", JSON.stringify(cart));
		alert(`${productQuantity}x ${productName} added to cart!`);
		displayTotalPrice();
	}
}


// Function to retrieve cart items
function getCart() {
    return JSON.parse(sessionStorage.getItem("cart")) || [];
}

// Attach event listener to the order button
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("order-button").addEventListener("click", function (event) {
        event.preventDefault(); // Prevent form submission
        let productId = document.getElementById("modal-prodId").value;
        let productName = document.getElementById("modal-title").innerText;
        let quantity = document.querySelector("input[name='product_quantity']").value || 1; // Default to 1 if empty
        addToCart(productId, productName, quantity);
    });
});*/
