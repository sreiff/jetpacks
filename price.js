
    //Adapted from Harriet Fell's Starship Pinafore (http://cs4550.com/starshipPinafore-02/services.html)
    var priceArray = [];

    function showPrices(frm){
    var message = "Your rental price:\n\n"

    var total = new Number(0);
      //see if first box is checked
      if (frm.time[0].checked && frm.quantity.value != 0)
      {
         total = frm.time[0].value * frm.quantity.value;
         message = message + "total:  $ " +  total.toFixed(2);
        if(!(alert(message))){};
      }
      //see if second box is checked
      else if (frm.time[1].checked && frm.quantity.value != 0)
      {
         total = frm.time[1].value * frm.quantity.value;
         message = message + "total:  $ " +  total.toFixed(2);
        if(!(alert(message))){};
      }
      //otherwise ask person to choose box
      else alert("Please choose a time and number of riders");
   
}
