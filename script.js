"use strict";

let table = document.querySelector("table");

table.addEventListener("click", function(e) {
  let id;

  if ((id = e.target.dataset.id)) {
    let data = new FormData();
    data.set("id", id);

    let xhr = new XMLHttpRequest();

    xhr.open("POST", "index.php", true);

    xhr.send(data);

    let tbody = e.target.parentElement.parentElement.parentElement;
    let row = e.target.parentElement.parentElement;

    row.style.opacity = 0;

    setTimeout(() => {
      tbody.removeChild(row);
    }, 1000);
  }
});
