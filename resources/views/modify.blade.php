<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <style>
        /* Your existing CSS styles */
    </style>
</head>
<body>
    <h2>Edit Category</h2>

    <form action="{{ route('category.update', $category->id) }}" method="POST" class="my-form">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="categoryName">Category Name:</label>
            <input type="text" name="category_name" class="form-control" id="categoryName" value="{{ $category->categories }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</body>
</html>
