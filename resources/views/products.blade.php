<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{mix('/css/app.css')}}">
    <link rel="stylesheet" href="{{mix('/css/theme.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">

    <title>Products managment</title>
   
</head>
<body>
    <div class="container mt-5">
        <!-- Header Section -->
        <header class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <button type="button" class="btn btn-primary mb-3 shadow-lg" data-bs-toggle="modal" data-bs-target="#createProductModal">
                    <i class="fa-solid fa-plus me-2"></i>Add New Product
                </button>
            </div>
            <div>
                <a href="/categories" class="btn btn-secondary mb-3 d-flex align-items-center shadow-lg">
                    <i class="fa-solid fa-list me-2"></i>Manage Categories
                </a>
            </div>
        </header>
    
        <!-- Product List Section -->
        <section id="product-list" class="table-responsive bg-white p-4 rounded shadow">
            <h3 class="mb-3 text-center text-primary">Product Inventory</h3>
            <table id="productTable" class="table table-hover table-striped">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">Product</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="product-table-body">
                    <!-- Product will be dynamically injected here -->
                </tbody>
            </table>
        </section>
    </div>
    

        <!-- Modal for creating product -->

        <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductModalLabel">Create New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createProductForm">
                            <div class="mb-3">
                                <label for="productName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="productName" required>
                            </div>
                            <div class="mb-3">
                                <label for="productDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="productDescription" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="productPrice" class="form-label">Price</label>
                                <input type="number" class="form-control" id="productPrice" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="productCategory" class="form-label">Category</label>
                                <select class="form-select" id="productCategory" required>
                                    <option value="" disabled selected>Select a category</option>
                                    <!-- Categories will be fetched here dynamically -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="productImage" class="form-label">Image</label>
                                <input type="file" class="form-control" id="productImage" accept="image/*">
                                <!-- Image preview -->
                                <div class="mt-3">
                                    <img id="imagePreview" src="" alt="Image Preview" class="img-thumbnail" 
                                    style=" display: none;width: 100px; height: 100px; object-fit: cover; border-radius: 50%;"
                                    >
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

    <!-- Modal for updating product -->
    <div class="modal fade" id="updatedProductModal" tabindex="-1" aria-labelledby="updatedProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="text-center p-3">
                    <img
                        id="updatedProductImagePreview"
                        src=""
                        alt="Product Image"
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;"
                    />
                </div>
                <div class="modal-body">
                    <h5 class="text-center mb-4">Update Product</h5>
                    <form id="updateProductForm">
                        <div class="mb-3">
                            <label for="updatedProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="updatedProductName" />
                        </div>
                        <div class="mb-3">
                            <label for="updatedProductDescription" class="form-label">Product Description</label>
                            <textarea class="form-control" id="updatedProductDescription" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="updatedProductPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="updatedProductPrice" step="0.01" />
                        </div>
                        <div class="mb-3">
                            <label for="updatedProductCategory" class="form-label">Category</label>
                            <input type="text" class="form-control" id="updatedProductCategory" />
                        </div>
                        <div class="mb-3">
                            <label for="updatedProductImage" class="form-label">Update Product Image</label>
                            <input type="file" class="form-control" id="updatedProductImage" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveProductBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    
  
    
    {{-- <form id="productForm">
        <h2>Create New Product</h2>

        <!-- Product Name -->
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter product name" required>

        <!-- Description -->
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" placeholder="Enter product description" required></textarea>

        <!-- Price -->
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" placeholder="Enter price" required>

        <!-- Category -->
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <option value="" disabled selected>Select a category</option>
            <option value="6">Category 1</option>
            <option value="2">Category 2</option>
            <option value="3">Category 3</option>
        </select>

        <!-- Image -->
        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <!-- Submit Button -->
        <button type="submit">Create Product</button>
    </form> --}}

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ mix('js/products/index.js') }}"></script>

  

</body>
</html>
