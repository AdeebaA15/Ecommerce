@include('layouts.sidebar');
<!DOCTYPE html>
<html>
<head>
    <style>
        input[type=text], select {
            width: 90%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 50%;
            box-sizing: border-box;
            margin-left:270px;
        }
        #category{
            margin-left:270px;
        }
/* 
        input[type=submit], button {
            width: 50%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        } */

        

        div {
            border-radius: 5px;
   
            padding: 10px;
            
        }

        table {
            width: 50%;
          
        margin-left:300px;
        }


        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: grey;
            color: white;
        }

        .btn-group {
            display: flex;
            justify-content: center;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div>
   
    <form action="{{ route('category.store') }}" method="POST" >
        @csrf
        <label for="categoryName" id="category" class="form-label">Category Name:</label><br>
        <input type="text" name="categories" class="form-control" id="categoryName" placeholder="Enter category name" required>
        <input type="submit" value="Submit" class="btn btn-primary">
    </form>
</div>

<div>
    <h3 style="text-align: center; ">All Categories</h3>
    <table >
        <tr class="tble" style="background-color: #ccc;">
            <th>ID</th>
            <th>Category Name</th>
            <th>Actions</th>
        </tr>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->categories }}</td>
                <td class="btn-group">
                    <form action="{{ route('category.edit', $category->id) }}" method="GET" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary" border: none;">Edit</button>
                    </form>
                    
                    <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>