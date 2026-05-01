$(document).ready(function() {
  $('#nimertis_form').on('submit', function(e) {
    e.preventDefault();
    
   const nimertis_link = $('#nimertis_link').val();

    // Φτιάχνεις FormData object
    const formData = new FormData();
    
    formData.append("nimertis_link", nimertis_link);

   $.ajax({
  type: 'POST',
  url: 'nimertis_post.php',
  data: formData,
  processData: false,
  contentType: false,
  dataType: 'json', // <-- this line
  success: function(data) {
    // data is already an object
    if (data.ok) {
      alert(data.message);
    } else {
      alert(data.error);
    }
  }
});

  });
  
});