

   $(document).ready(function() {
  $('#pres_details').on('submit', function(e) {
    e.preventDefault();
 
   $pres_date = $('#pres_date').val();
   $pres_time = $('#pres_time').val();
   $exam_type = $('input[name="exam_type"]:checked').val();
   $pres_room = $('#room').val();
   $press_link = $('#press_link').val();
   
    const formData = new FormData();
    formData.append("pres_date", $pres_date);
    formData.append("pres_time", $pres_time);
    formData.append("exam_type", $exam_type);
    if ($exam_type == "online"){
      formData.append("pres_link",$press_link); 
    }else{
      formData.append("room",$pres_room);
    }


   $.ajax({
  type: 'POST',
  url: 'presentation.php',
  data: formData,
  processData: false,
  contentType: false,
  dataType: 'json', 
  success: function(data) {
 
    if (data.ok) {
      alert("Eπιτυχημένη Καταχώρηση Ημερομηνίας Παρουσίασης Διπλωματικής Εργασίας");
    } else {
      alert(data.error);
    }
  }
});

  });
});
  












