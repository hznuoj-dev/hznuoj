<?php $title="Reset Password";?>
<?php include "header.php" ?>

<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered" style="max-width: 600px;">
    <br>
      <h1>Reset Password Page</h1>
    <hr>
    <form action="resetpassword.php" method="post" class="am-form am-form-horizontal" data-am-validator>
      <?php include_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/set_post_key.php"?>
      <h6 style="margin: 0 auto;">检测到您的密码为弱口令，请重新设置密码！</h6>
      <h6 style="margin: 0 auto;">新密码必须由大写字母、小写字母和数字组成，且长度为6~22位。</h6><br>
      <div class="am-form-group">
        <label for="npwd" class="am-u-sm-4 am-form-label">New Password: </label>
        <div class="am-u-sm-8">
          <input type="password" name="npassword" id="npwd" value="" placeholder="Your Password" style="width:100%;"
          pattern="^.*(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])\w" required/>
        </div>
      </div>
      <div class="am-form-group">
        <label for="rpwd" class="am-u-sm-4 am-form-label">Repeat Password: </label>
        <div class="am-u-sm-8">
          <input type="password" name="rpassword" id="rpwd" value="" placeholder="Repeat Your Password" style="width:100%;"
          data-equal-to="#npwd" required/>
        </div>
      </div>
      <div class="am-from-group">
        <div class="am-cf am-u-sm-offset-4 am-u-sm-8 am-u-end" style="text-align: center">
            <div style="display: inline-block;">
              <input type="submit" name="submit" value="submit" class="am-btn am-btn-primary am-btn-sm am-fl" style="width: 100px;">
            </div>
        </div>
      </div>
    </form>
  </div>
  <br>
  <br>
</div>

<?php include "footer.php" ?>
