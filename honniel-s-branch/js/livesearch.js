$(document).ready(function(){
  // Function to fetch all data
  function fetchAllData() {
      $.ajax({
          url: "livesearch.php",
          method: "POST",
          data: { input: "" }, // Sending empty input to fetch all data
          success: function(data){
              $("#searchresult").html(data);
              $("#searchresult").css("display", "block");
          }
      });
  }

  // Call fetchAllData function initially to display all data
  fetchAllData();

  // Keyup event for live search
  $("#live_search").keyup(function(){
      var input = $(this).val();
      if(input != ""){
          $.ajax({
              url: "livesearch.php",
              method: "POST",
              data: { input: input },
              success: function(data){
                  $("#searchresult").html(data);
                  $("#searchresult").css("display", "block");
              }
          });
      } else {
          $("#searchresult").html(""); // Clear search result if input is empty
          fetchAllData(); // Fetch all data again when input is empty
      }
  });
});

// Function to confirm delete
function confirmDelete(userName) {
    return confirm('Are you sure you want to delete ' + userName + '?');
}

// Event listener for delete button
$(document).on("submit", "form", function() {
    var userName = $(this).find(".user-name").val(); // Assuming you have a hidden input with class "user-name" containing the user's name
    return confirmDelete(userName);
});