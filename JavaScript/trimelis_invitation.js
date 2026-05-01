document.addEventListener("DOMContentLoaded", function() {
  console.log("JavaScript Loaded!");  //for debugging purpose
    fetch("get_professorsList.php" )
      .then (response => response.json())  // convert to json
      .then (data => {
          let select = document.getElementById("professors");
          data.forEach ( prof => {
                let option = document.createElement("option");
                option.value = prof.Professor_User_ID;
                //we set the value of the proffesor id
                option.textContent = prof.name;  //add the name in text form 
                  select.appendChild(option); //add the proffesor to the drop down menu
                   });
          });
          document.getElementById("trimelisForm").addEventListener("submit", function(event) {
              event.preventDefault();
              let selectedProfessors = Array.from(document.getElementById("professors").selectedOptions).map(option => option.value);
              if(selectedProfessors.length < 2) {
                  //alert("select at least two professors ") ;
                  return ; 
              }
              //check for data stringify.
              console.log("Sending data:", JSON.stringify({  professors: selectedProfessors }));

              fetch('send_invitation_trimelis.php' ,{  //creating post request to the server
                method : 'POST',
                headers: {
                  'Content-Type': 'application/json'    //we "tell" the server that we are sending JSON data
                },
                body :  JSON.stringify({  professors : selectedProfessors  }) //making object to JSON string 
                
              })
           
                .then(response => response.json())   //handles the asychronous response and parse the JSON data
                
                .catch(error => console.error("Error:" , error )); //error handling
                
  
          });
              
  });


















