<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90 p-4">
                <div class="card-body">
                    <h4>SET NEW PASSWORD</h4>
                    <br/>
                    <label>New Password</label>
                    <input id="password" placeholder="New Password" class="form-control" type="text"/>
                    <br/>
                    <label>Confirm Password</label>
                    <input id="cpassword" placeholder="Confirm Password" class="form-control" type="text"/>
                    <br/>
                    <button onclick="ResetPass()" class="btn w-100 bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  async function ResetPass() {
         /**
          * Retrieves the password value from the HTML element with the id 'password' and stores it in the 'password' variable.
          *
          * @type {string} password The value of the password input field.
          */
         let password = String(document.getElementById('password').value);
        let cpassword = String(document.getElementById('cpassword').value);

        if(password.length===0){
            errorToast('Password is required')
        }
        else if(cpassword.length===0){
            errorToast('Confirm Password is required')
        }
        else if(password!==cpassword){
            errorToast('Password and Confirm Password must be same')
        }
        else{
          showLoader()
          let res=await axios.post("/reset-password",{password:password});
          hideLoader();
          if(res.status===200 && res.data.success){
              successToast(res.data['message']);
              setTimeout(function () {
                  window.location.href="/login";
              },1000);
          }
          else{
            errorToast(res.data['message'])
          }
        }

    }
</script>
