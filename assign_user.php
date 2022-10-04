<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Assign Employee to Manager</title>
</head>
<body>
  <div class="container"  class="col-md-4">
	<h1>Assign Employee to Manager</h1>
	<hr/>
 <table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">Manager</th>
      <th scope="col">Employee</th>
      <th scope="col">Add Employee</th>
    </tr>
  </thead>
  <tbody id="table">
   
  </tbody>
</table>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Assign Employee to the Manager</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="container">
      <table class="table table-bordered">
      <thead>
      </thead>
      <tbody><tr><td>
      <select aria-label="Default select example" name="employee" id="employee_info" class="form-control">
     <input type="hidden" name="manager_id" id="option_manager_id"/>
     </tbody>
    </table>
      	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="assignEmployee()">Assign Employee</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>