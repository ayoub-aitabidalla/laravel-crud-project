<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{mix('/css/app.css')}}">
    <link rel="stylesheet" href="{{mix('/css/theme.css')}}">
    <title>Categories managment</title>
   
</head>
<body>
    <div class="container my-5">
    
        <div class="card mb-4 px-5 pt-3 pb-4 bg-light shadow-lg border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold text-primary">Create New Category</h5>
                <form id="addCategoryForm" class="d-flex">
                    @csrf
                    <input type="text" id="categoryName" class="form-control me-3" placeholder="Enter category name">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Add</button>
                </form>
            </div>
        </div>
    
        <section class="bg-white p-4 rounded shadow">
            <h3 class="text-primary mb-3">Category List</h3>
            <table class="table table-hover table-striped">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic content will go here -->
                </tbody>
            </table>
        </section>
    </div>
    

    <div class="modal fade" id="updatedCategoryModal" tabindex="-1" aria-labelledby="updatedCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <h5 class="text-center mb-4">Update Category</h5>
                    <form id="updateCategoryForm">
                        <div class="mb-3">
                            <label for="updatedCategoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="updatedCategoryName" />
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ mix('js/categories/index.js') }}"></script>

</body>
</html>