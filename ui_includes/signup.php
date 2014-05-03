<html>
<body>
<?php 
include 'header.php';
?>
<?php 
if (!$registration->registration_successful && !$registration->verification_successful) 
{
  if (count($registration->errors) > 0) echo $registration->errors[0];
?>
<form action="signup.php" method="post">
<table style="text-align: left;" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td>Email</td>
      <td><input name="a" type="hidden" value="new"><input name="email" required></td>
    </tr>
    <tr>
      <td>Re-enter Email</td>
      <td><input name="email1" required></td>
    </tr>
    <tr>
      <td>Name</td>
      <td><input name="name" required></td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Address</td>
      <td><textarea cols="20" rows="3" name="address" required></textarea></td>
    </tr>
    <tr>
      <td>Locality</td>
      <td><input name="locality" required></td>
    </tr>
    <tr>
      <td>City</td>
      <td>
      <select size="1" name="city" required>
        <option value=""> </option>
        <option value="Manipal">Manipal</option>
        <option value="Udupi">Udupi</option>
      </select>
      </td>
    </tr>
    <tr>
      <td>State</td>
      <td>
      <select size="1" name="state" required>
        <option value="Karnataka">Karnataka</option>
      </select>
      </td>
    </tr>
    <tr>
      <td>Phone number (mobile preferred)</td>
      <td><input name="phone"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
    </tr>
    <tr>
      <td>Username</td>
      <td><input name="username" pattern="[a-zA-Z0-9]{2,64}" required></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><input name="pwd" type="password" pattern=".{6,}" required autocomplete="off"></td>
    </tr>
    <tr>
      <td>Re-enter Password</td>
      <td><input name="pwd2" type="password" pattern=".{6,}" required autocomplete="off"></td>
    </tr>
    <tr><td></td><td><img src="tools/showCaptcha.php" alt="captcha" /><br/>
      <label>Please enter these characters</label><br/>
      <input type="text" name="captcha" required /></td></tr>
        <tr>
      <td></td>
      <td><input type="submit" name="register" value="Join!" /></td>
    </tr>
  </tbody>
</table>
</form>
<?php
 }
 else
 {
       if (count($registration->messages) > 0) echo $registration->messages[0];
 } 
?>
<?php include 'footer.php'; ?>