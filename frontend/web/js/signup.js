function myFunction() {
  // check postcode
  if ((map_cal) == 0){
  var shipment2 = $("input[name='SignupForm\[shipment_postcode2\]']").val();

  var regPostcode = /[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i;

  if(regPostcode.test(shipment2) == false){
    document.getElementById('myModal2').click();
    document.getElementById('popupsign').innerHTML = ('Postcode is not valid');
    return false;
  }

  $.ajax({
    url: '/vendor/checkp.php',
    data: "f=checkPostalCSV&p=" + $("#signupform-shipment_postcode2").val(),
    type: "GET",
    success: function (response) {
      if(response == "true"){
        // document.getElementById('myModal2').click();
        // document.getElementById('popupsign').innerHTML = ('Postcode is correct');
        document.getElementById('submitSignUp').click();
      }else{
        document.getElementById('myModal2').click();
        document.getElementById('popupsign').innerHTML = ("Sorry, we currently don\'t deliver to your postcode");
        return false;
      }
    }
  });
}

// maybe use this if last postcode needs to verify
if ((map_cal) == 1){
  var shipment2 = $("input[name='SignupForm\[shipment_postcode2\]']").val();

  var regPostcode = /[0-9a-zA-Z]{0,1}[a-zA-Z]{2}/i;

  if(regPostcode.test(shipment2) == false){
    document.getElementById('myModal2').click();
    document.getElementById('popupsign').innerHTML = ('Postcode is not valid');
    return false;
  }else{
    document.getElementById('submitSignUp').click();
  }
}

};
