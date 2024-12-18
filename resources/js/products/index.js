import axios from "axios";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";
import * as bootstrap from "bootstrap";

// To store the DataTable instance
let productTable;
// To store the list of products globally
let products = [];
const apiUrl = "http://localhost:8000/api/products";

document.addEventListener("DOMContentLoaded", async () => {
    productTable = new DataTable("#productTable", {
        responsive: true,
        orderable: true,
        createdRow: function (row) {
            // Apply text-center class to the relevant columns
            $("td", row).eq(1).addClass("text-center"); // Description
            $("td", row).eq(2).addClass("text-center"); // Price
            $("td", row).eq(3).addClass("text-center"); // Category
            $("td", row).eq(4).addClass("text-center"); // Actions
        },
        iDisplayLength: 5,
        aLengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
        ],
    });

    fetchProducts();

    // Attach row click event
    document
        .querySelector("#productTable tbody")
        .addEventListener("click", (e) => {
            const deleteButton = e.target.closest("button[data-id]");
            if (deleteButton) {
                e.stopPropagation(); // Prevent row click from triggering
                const productId = deleteButton.getAttribute("data-id");
                deleteProduct(productId);
                return;
            }

            // Check if the clicked element is within the first column
            const firstColumn = e.target.closest("td:first-child");
            if (!firstColumn) return;

            const row = e.target.closest("tr"); // Get the clicked row
            if (!row) return;

            // Get the row index and associated product data
            const rowIndex = productTable.row(row).index();
            const productId = products[rowIndex]?.id;
            const product = products.find((p) => p.id == productId);

            if (product) {
                document.getElementById("updatedProductName").value =
                    product.name;
                document.getElementById("updatedProductDescription").value =
                    product.description;
                document.getElementById("updatedProductPrice").value =
                    product.price;
                document.getElementById("updatedProductCategory").value =
                    product.category.name;
                document.getElementById(
                    "updatedProductImagePreview"
                ).src = `/storage/${product.image}`; // Show the current image

                // Show the modal
                const modal = new bootstrap.Modal(
                    document.getElementById("updatedProductModal")
                );
                modal.show();

                // Set the action to update the product
                document.getElementById("saveProductBtn").onclick =
                    function () {
                        updateProduct(productId, product.category.id);
                    };
            }
        });

    document
        .getElementById("createProductForm")
        .addEventListener("submit", handleCreateProduct);

    const categories = await fetchCategories();

    const categorySelect = document.getElementById("productCategory");
    categories.forEach((category) => {
        const option = document.createElement("option");
        option.value = category.id;
        option.textContent = category.name;
        categorySelect.appendChild(option);
    });
});

// Fetch products from the server
async function fetchProducts() {
    try {
        const response = await axios.get(apiUrl);
        products = response.data.products; // Assign data to the global variable products

        // Call the function to render products
        renderProducts(products);
    } catch (error) {
        console.error("Error fetching products:", error);
    }
}

// Render products dynamically in the table
function renderProducts(products) {
    // Clear existing rows from the DataTable
    productTable.clear();

    // Add each product as a new row in the DataTable
    products.forEach((product) => {
        const deleteButton = `<button class="btn btn-outline-danger btn-sm me-1 shadow-sm" data-id="${product.id}">
                                  <i class="fas fa-trash"></i>
                              </button>`;

        const row = productTable.row
            .add([
                `<td class="d-flex align-items-center">
                    <img src="/storage/${product.image}" 
                         alt="${product.name}" 
                         class="product-image me-3" 
                         loading="lazy" />
                    <span class="product-name">${product.name}</span>
                </td>`,
                `<td class="text-muted">${product.description}</td>`,
                `<td><span class="fw-bold text-success">$${product.price}</span></td>`,
                `<td class="text-primary">${product.category.name}</td>`,
                `<td>${deleteButton}</td>`,
            ])
            .node();

        // Add event listener to the delete button
        $(row)
            .find("button[data-id]")
            .on("click", function () {
                const productId = $(this).data("id");
                deleteProduct(productId);
            });
    });

    productTable.draw();

    // Redraw the DataTable to reflect the changes
    productTable.draw();
}

// Update a product
async function updateProduct(productId, categoryId) {
    const name = document.getElementById("updatedProductName").value;
    const description = document.getElementById(
        "updatedProductDescription"
    ).value;
    const price = document.getElementById("updatedProductPrice").value;
    const image = document.getElementById("updatedProductImage").files[0];

    const formData = new FormData();
    formData.append("name", name);
    formData.append("description", description);
    formData.append("price", price);
    formData.append("category_id", categoryId);
    if (image) {
        formData.append("image", image);
    }

    try {
        const response = await axios.post(
            `${apiUrl}/${productId}?_method=PUT`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        fetchProducts();

        const modalElement = document.getElementById("updatedProductModal");
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        modalInstance.hide();
        showToast(response.data.message);
    } catch (error) {
        console.error(error);
        handleError(error.response?.data?.message);
    }
}

// Delete a product
async function deleteProduct(productId) {
    try {
        const response = await axios.delete(`${apiUrl}/${productId}`);
        fetchProducts();
        showToast(response.data.message);
    } catch (error) {
        handleError(error.response?.data?.message);
    }
}

//create product
async function handleCreateProduct(event) {
    event.preventDefault();

    // Get form data
    const name = document.getElementById("productName").value;
    const description = document.getElementById("productDescription").value;
    const price = document.getElementById("productPrice").value;
    const category = document.getElementById("productCategory").value;
    const image = document.getElementById("productImage").files[0];

    // Create form data to send to the server
    const formData = new FormData();
    formData.append("name", name);
    formData.append("description", description);
    formData.append("price", price);
    formData.append("category_id", category);
    formData.append("image", image);

    try {
        const response = await axios.post(apiUrl, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        fetchProducts();

        const modalElement = document.getElementById("createProductModal");
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        modalInstance.hide();
        document
            .querySelectorAll(".modal-backdrop")
            .forEach((backdrop) => backdrop.remove());
        document.body.classList.remove("modal-open");

        // Show success message
        showToast(response.data.message);

        document.getElementById("productName").value = "";
        document.getElementById("productDescription").value = "";
        document.getElementById("productPrice").value = "";
        document.getElementById("productCategory").value = "";
        document.getElementById("productImage").value = "";
        document.getElementById("imagePreview").src = "";
        document.getElementById("imagePreview").style.display = "none";
    } catch (error) {
        console.error(error);
        handleError(error.response?.data?.message);
    }
}

async function fetchCategories() {
    const apiUrl = "http://localhost:8000/api/categories"; // categories API URL
    try {
        const response = await axios.get(apiUrl);
        return response.data;
    } catch (error) {
        console.error("Error fetching categories:", error);
        return [];
    }
}

document
    .getElementById("productImage")
    .addEventListener("change", function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById("imagePreview");

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };

            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            preview.style.display = "none";
        }
    });

// Show toast messages
function showToast(message, type = "success") {
    const colors = {
        success: "linear-gradient(to right, #00b09b, #96c93d)",
        error: "linear-gradient(to right, #ff5f6d, #ffc371)",
        warning: "linear-gradient(to right, #ffc107, #ffd85d)",
    };

    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "right",
        style: { background: colors[type] || colors.success },
        close: true,
        stopOnFocus: true,
    }).showToast();
}

// Handle errors
function handleError(error) {
    const message =
        error.response?.data?.message || "An unexpected error occurred.";
    showToast(message, "error");
    console.error(error);
}
