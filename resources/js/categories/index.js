import axios from 'axios';
import Toastify from 'toastify-js';
import "toastify-js/src/toastify.css";
import * as bootstrap from "bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    const addCategoryForm = document.getElementById('addCategoryForm');
    const categoryNameInput = document.getElementById('categoryName');
    const tableBody = document.querySelector("table.table-hover tbody");
    const apiUrl = "http://localhost:8000/api/categories";
    const updatedCategoryModal = new bootstrap.Modal(document.getElementById('updatedCategoryModal'));
    const updatedCategoryNameInput = document.getElementById('updatedCategoryName');
    const saveCategoryBtn = document.getElementById('saveCategoryBtn');

    // Fetch categories on page load
    fetchCategories();

    // Handle create new category form
    addCategoryForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const categoryName = categoryNameInput.value.trim();

        if (!categoryName) {
            showToast("Category name cannot be empty.", "warning");
            return;
        }

        try {
            const response = await axios.post(apiUrl, { name: categoryName });
            showToast(response.data.message, "success");
            categoryNameInput.value = ''; 
            fetchCategories(); 
        } catch (error) {
            handleError(error);
        }
    });

    // Fetch categories list
    async function fetchCategories() {
        try {
            const response = await axios.get(apiUrl);
            const categories = response.data;

            tableBody.innerHTML = ''; 
            if (categories.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="2" class="text-center">No categories found.</td>
                    </tr>
                `;
                return;
            }

            categories.forEach(category => {
                const row = `
                    <tr id="category-row-${category.id}">
                        <td>${category.name}</td>
                        <td class="text-end">
                            <button class="icon-btn btn btn-outline-info edit-btn" data-id="${category.id}" data-name="${category.name}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="icon-btn btn btn-outline-danger delete-btn" data-id="${category.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });

            attachListeners();
        } catch (error) {
            console.error("Error fetching categories:", error);
        }
    }

    // Attach event listeners for edit and delete buttons
    function attachListeners() {
        const editButtons = document.querySelectorAll(".edit-btn");
        const deleteButtons = document.querySelectorAll(".delete-btn");

        editButtons.forEach(button => {
            button.addEventListener("click", () => {
                const categoryId = button.getAttribute("data-id");
                const categoryName = button.getAttribute("data-name");

                // Open modal
                updatedCategoryNameInput.value = categoryName;
                saveCategoryBtn.setAttribute("data-id", categoryId);
                updatedCategoryModal.show();
            });
        });

        deleteButtons.forEach(button => {
            button.addEventListener("click", () => {
                const categoryId = button.getAttribute("data-id");
                deleteCategory(categoryId);
            });
        });
    }

    // Delete a category
    async function deleteCategory(id) {
        try {
            const response = await axios.delete(`${apiUrl}/${id}`);
            showToast(response.data.message, "success");
            document.getElementById(`category-row-${id}`).remove();
        } catch (error) {
            handleError(error);
        }
    }

    // Save updated category
    saveCategoryBtn.addEventListener('click', async () => {
        const updatedName = updatedCategoryNameInput.value.trim();
        const categoryId = saveCategoryBtn.getAttribute("data-id");

        if (!updatedName) {
            showToast("Category name cannot be empty.", "warning");
            return;
        }

        try {
            const response = await axios.put(`${apiUrl}/${categoryId}`, { name: updatedName });
            showToast(response.data.message, "success");
            updatedCategoryModal.hide();
            fetchCategories(); 
        } catch (error) {
            handleError(error);
        }
    });

    // Show toast messages
    function showToast(message, type = "success") {
        const colors = {
            success: "linear-gradient(to right, #00b09b, #96c93d)",
            error: "linear-gradient(to right, #ff5f6d, #ffc371)",
            warning: "linear-gradient(to right, #ffc107, #ffd85d)"
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
        const message = error.response?.data?.message || "An unexpected error occurred.";
        showToast(message, "error");
        console.error(error);
    }
});
