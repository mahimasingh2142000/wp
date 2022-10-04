// jQuery(document).ready(function(){
//     //alert('hii');
//       // $("p").click(function(){
//       //   $(this).hide();
//       // });
//       jQuery("#form").validate({
//         rules: {
//            fname: 'required',
//            lname: 'required',
//            mobile: {
//             phonenu: true,
//             phoneUK: true,
//             required: true,
//             minlength: 10,
//             maxlength: 10
//           },
//            email: {
//               required: true,
//               email: true,//add an email rule that will ensure the value entered is valid email id.
//               maxlength: 255,
//            },
//            password:{
//             required: true,
//             minlength: 5,
//            },
//         //    messages: {
//         //     fname: 'This field is required',
//         //     lname: 'This field is required',
//         //     mobile:'This field take length 10',
//         //     email: 'Enter a valid email',
//         //  },
//          submitHandler: function(form) {
//           form.submit();
//        }
//         }
//      });
//   });


function customValidation() {
   // First Name Validation
   var fname = document.getElementById("exampleInputFirstName");
   firstNameValue = fname.value.trim();
   validFirstName = /^[A-Za-z]+$/;
   firstNameErr = document.getElementById('first-name-err');
   if (firstNameValue == "") {
      firstNameErr.innerHTML = "First Name is required";
      return false;
   } else if (!validFirstName.test(firstNameValue)) {
      firstNameErr.innerHTML = "First Name must be only string without white spaces";
      return false;
   } else {
      firstNameErr.innerHTML = "";
   }

   // Last Name Validation
   var lname = document.getElementById("exampleInputLastName");
   lastNameValue = lname.value.trim();
   validLastName = /^[A-Za-z]+$/;
   lastNameErr = document.getElementById('last-name-err');

   if (lastNameValue == "") {
      lastNameErr.innerHTML = "Last Name is required";
      return false;
   } else if (!validLastName.test(lastNameValue)) {
      lastNameErr.innerHTML = "Last Name must be only string without white spaces";
      return false;
   } else {
      lastNameErr.innerHTML = "";
   }

   // Email Address Validation
   var emailAddress = document.getElementById("exampleInputEmail1");
   emailAddressValue = emailAddress.value.trim();
   validEmailAddress = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
   emailAddressErr = document.getElementById('email-err');
   if (emailAddressValue == "") {
      emailAddressErr.innerHTML = "Email Address is required";
      return false;
   } else if (!validEmailAddress.test(emailAddressValue)) {
      emailAddressErr.innerHTML = "Email Address must be in valid formate with @ symbol";
      return false;
   } else {
      emailAddressErr.innerHTML = "";
   }

   // Mobile Number Validation
   var mobileNumber = document.getElementById("exampleInputMobile");
   mobileNumberValue = mobileNumber.value.trim();
   validMobileNumber = /^[0-9]*$/;
   mobileNumberErr = document.getElementById('mobile-number-err');
   if (mobileNumberValue == "") {
      mobileNumberErr.innerHTML = "Mobile Number is required";
      return false;
   } else if (!validMobileNumber.test(mobileNumberValue)) {
      mobileNumberErr.innerHTML = "Mobile Number must be a number";
      return false;
   } else if (mobileNumberValue.length != 10) {
      mobileNumberErr.innerHTML = "Mobile Number must have 10 digits";
      return false;
   }
   else {
      mobileNumberErr.innerHTML = "";
   }


   // Password Validation
   var password = document.getElementById("exampleInputPassword1");
   passwordValue = password.value.trim();
   validPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

   passwordErr = document.getElementById('password-err');
   if (passwordValue == "") {
      passwordErr.innerHTML = "Password is required";
      return false;
   } else if (!validPassword.test(passwordValue)) {
      passwordErr.innerHTML = "Password must have at least one Uppercase, lowercase, digit, special characters & 8 characters";
      return false;
   }
   else {
      passwordErr.innerHTML = "";
   }
}
jQuery(function () {
   jQuery('form').on('submit', function (e) {
      e.preventDefault();
      if (customValidation() != true) {
         jQuery.ajax({
            type: 'post',
            url: 'http://localhost/jamtech/wordpress/wp-admin/admin-ajax.php',
            data: jQuery('form').serialize(),
            success: function (result) {
               if (result.data) {
                  Swal.fire(
                  'Form Submitted!',
                  'Successfully!',
                  'success'
                  )
               }
               else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Form Submition Failed!',
                    footer: '<a href="">Pleace Enter Try Again</a>'
                  })       
               }
            }
         });
      }
   });
});

jQuery(document).ready(function () {
   jQuery.ajax({
      url: 'http://localhost/jamtech/wordpress/wp-json/state/v1/author/44',
      dataType: 'JSON',
      success: function (data) {
         for (var i = 0; i<data.length; i++) {
            jQuery('#state').append(`<option  value="${data[i]['s_name']}">
              ${data[i]['s_name']}
            </option>`);
        }
      }
   });
});

jQuery(function () {
   jQuery('#state').on('change', function () {
      jQuery('#city').find('.option').remove();
      const arr_state={UttarPradesh:1,Chhattisgarh:2,Karnataka:3};
      var selected_state=jQuery('#state').val().replace(" ", "");
      var id=arr_state[selected_state];
      jQuery.ajax({
         url: 'http://localhost/jamtech/wordpress/wp-json/city/v1/author/' + id,
         dataType: 'JSON',
         success: function (data) {
            for (var i = 0;  i<data.length; i++) {
               jQuery('#city').append(`<option class="option" value="${data[i]['c_name']}">
               ${data[i]['c_name']}
               </option>`);
            }
         }
      });
   });
});
jQuery(document).ready(function () {
   jQuery('#table').empty();
   jQuery.ajax({
      type:'post',
      url: 'http://localhost/jamtech/wordpress/wp-json/api/v1/get_assign_users',
      dataType: 'JSON',
      success: function (data) {
         for (var i = 0; i<data['data'].length; i++){ 
            if(data['data'][i]['m_name']){
               const emp_data = data['data'][i]['emp_data'];
               var emp_name = [];
               for (var j = 0; j<emp_data.length; j++) {
                  //console.log(data['data'][i]['m_id']);
                  //console.log(emp_data[j]['e_id']);
                  emp_name.push(emp_data[j]?.e_name + `<i class="bi bi-x-circle-fill emp_delete"></i><input type="hidden" id="employee_id" value="${emp_data[j]['e_id']}"/><input type="hidden" id="manager_id" value="${data['data'][i]['m_id']}"/>`);
               }
               jQuery('#table').append(`<tr>
                  <td>${data['data'][i]['m_name']}</td>
                  <td>${String(emp_name)}</td>
                  <td><i type="button" onclick="openModel('${data['data'][i]['m_id']}')" class="bi bi-plus-square-fill emp_btn"></i></td>
               </tr>`);
            }
         }
      }
   });
});   
function openModel(manager_id){
   var manager_id = manager_id;
   jQuery.ajax({
   type:'post',
   url: 'http://localhost/jamtech/wordpress/wp-json/api/v1/get_all_employee',
   dataType: 'JSON',
   success: function (data) {
      $('.modal').modal('show');
      const emp_data = data['data'];
      console.log(emp_data);
      var emp_data_option = "<option value=''>Select Option</option>";
      for(var i=0; i<emp_data.length;i++){
         emp_data_option += `<option class="emp_option" value="${emp_data[i]['e_id']}">${emp_data[i]['e_name']}</option>`
      }
      jQuery('#option_manager_id').val(manager_id);
      jQuery('#employee_info').html(emp_data_option);
      }
   });
}
function assignEmployee(){
  var manager_id = jQuery('#option_manager_id').val();
  var employee_id = jQuery('#employee_info').val();
  //console.log(manager_id,employee_id);
   jQuery.ajax({
      type: 'post',
      url: 'http://localhost/jamtech/wordpress/wp-json/api/v1/assign_employee',
      dataType: 'json',
      data: {'employee_id':employee_id,'manager_id':manager_id},
      success: function (result) {
         $('.modal').modal('hide');
         if(result.data){
            Swal.fire(
            'Employee Assigned!',
            'Successfully!',
            'success'
            )
         }else{
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Failed!',
              footer: '<a href="">Pleace Enter Try Again</a>'
            }) 
         }
      }
   });
}
jQuery(document).on('click', '.emp_delete', function() {
   var employee_id = jQuery('#employee_id').val();
   var manager_id = jQuery('#manager_id').val();
   Swal.fire({
      title: 'Are you sure? You want remove this Employee',
      showCancelButton: true,
      confirmButtonText: 'Remove',
      }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
         jQuery.ajax({
            type: 'post',
            url: 'http://localhost/jamtech/wordpress/wp-json/api/v1/remove_employee',
            dataType: 'json',
            data: {'employee_id':employee_id,'manager_id':manager_id},
            success: function (result) {
               console.log(result.data);
               if(result.data){
                    Swal.fire('Removed!', '', 'success')
                }else{
                   Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Failed!',
                    footer: '<a href="">Pleace Enter Try Again</a>'
                  }) 
                }
            }
         });
      } 
   })
});