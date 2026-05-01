document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("ajaxButtonS");
  const out = document.getElementById("myThesis");

  btn.addEventListener("click", () => {
    fetch("fetch_thesis.php")
      .then(res => res.json())
      .then(data => {
        // out.innerText = JSON.stringify(data, null, 2);
      })
      .catch(err => console.error("Fetch error:", err));
  
    data.forEach(item => {
      const div = document.createElement("div");
      div.className = "thesis";
      div.innerHTML = `
        <h3>$item.title</h3>
        <p>${item.description}</p>
        <p><b>Status:</b> ${item.status}</p>
        <p><b>Professor:</b> ${item.professor}</p>
        <p><b>Student:</b> ${item.student}</p>
        <p><b>Final grade:</b> ${item.final_grade}</p>
        ${item.pdf ? `<a href="${item.pdf}" target="_blank">Click to See</a>` : ""}
      `;
      container.appendChild(div);
    });
  })
  .catch(err => console.error("Fetch error:", err));
});