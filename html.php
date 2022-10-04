<html>
<head>

</head>
<body>
<form id="form" method="post">
 <div class="form-group">
    <label for="exampleInputFirstName">First Name</label>
    <input type="text" class="form-control" id="exampleInputFirstName" name="fname" error-message="This field is required">
  </div>
  <div id="first-name-err"></div>
  <div class="form-group">
    <label for="exampleInputLastName">Last Name</label>
    <input type="text" class="form-control" id="exampleInputLastName" name="lname">
  </div>
  <div id="last-name-err"></div>
  <div class="form-group">
    <label for="exampleInputGender">Gender</label>
    <input type="radio" class="form-control" id="exampleInputMale" name="gender" value="Male">   Male
    <input type="radio" class="form-control" id="exampleInputFemale" name="gender" value="Female">    Female
  </div>
  <div class="form-group">
  <label for="exampleInputMobile">Contact Number</label>
  <input type="number" class="form-control" id="exampleInputMobile" name="mobile">
</div>
<div id="mobile-number-err"></div>
 <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
    <small id="emailHelp" class="form-text text-muted">We-ll never share your email with anyone else.</small>
  </div>
  <div id="email-err"></div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
  </div>
  <div id="password-err"></div>
  <div class="form-group">
    <label for="SelectState">Select State</label>
    <select name="state" id="state">
      <option value="">Select State</option>
      <!-- <option value="" id="stateOption"></option> -->
    
    </select>
    <label for="SelectCity">Select City</label>
    <select name="city" id="city">
      <option value="">Select City</option>
    </select>
  </div>
  <div class="form-group">
    <label for="SelectManager">Select Role</label>
    <select name="role" id="role">
      <option value="">Select Role</option>
      <option value="manager">Manager</option>
      <option value="employee">Employee</option>
    </select>
  </div>
  <input type="hidden" name="action" value="user"/>
  <button type="submit" class="btn btn-primary">Submit</button><div id="message"></div>
</form>
</body>
</html>