<? include("inc/header.php"); ?>
<form id="tryitForm" class="form-horizontal">
    <div class="form-group">
        <label class="col-md-3 control-label">Full name</label>
        <div class="col-md-4">
            <input type="text" class="form-control" name="firstName" />
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="lastName" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label">Email address</label>
        <div class="col-md-6" style="display:none">
            <input type="text" class="form-control" name="email" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label">Gender</label>
        <div class="col-md-6">
            <div class="radio">
                <label><input type="radio" name="gender" value="male" /> Male</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="gender" value="female" /> Female</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="gender" value="other" /> Other</label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-offset-3 col-md-8">
            <button type="submit" class="btn btn-primary">Say hello</button>
        </div>
    </div>
</form>
<? include("inc/footer.php"); ?>
<script>
$(document).ready(function() {
    $('#tryitForm').bootstrapValidator({
        excluded: [':not(:visible)'],
        // submitButtons: 'button[type="button"]',
        feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
        },
        message: 'This value is not valid',
        fields: {
            firstName: {
                validators: {
                    notEmpty: {
                        message: 'The first name is required and cannot be empty'
                    }
                }
            },
            lastName: {
                validators: {
                    notEmpty: {
                        message: 'The last name is required and cannot be empty'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    }
                }
            },
            gender: {
                validators: {
                    notEmpty: {
                        message: 'The gender is required'
                    }
                }
            }
        },
        submitHandler: function(validator, form, submitButton) {
          $.post(form.attr('action'), form.serialize(), function(result) {
              // ... process the result ...
          }, 'json');
        }
    });
});
</script>
