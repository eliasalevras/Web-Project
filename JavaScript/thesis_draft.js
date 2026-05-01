$(document).ready(function() {
  $('#draft_material').on('submit', function(e) {
    e.preventDefault();
    
   
    const draft_thesis_PDF = $('#draft_thesis_PDF').val();
    const thesis_links = $('#thesis_links').val();

    // Φτιάχνεις FormData object
    const formData = new FormData();
    
    formData.append("draft_thesis_PDF", $('#draft_thesis_PDF')[0].files[0]);
    formData.append("thesis_links", thesis_links);

   $.ajax({
  type: 'POST',
  url: 'thesis_draft.php',
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