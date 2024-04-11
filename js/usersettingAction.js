function checkforblank(){

  if(document.getElementById('fname'.value== "")) {
    alert("Please enter your first name");
    document.getElementById('fname').style.bordercolor = "red";
    return false;
  }

}