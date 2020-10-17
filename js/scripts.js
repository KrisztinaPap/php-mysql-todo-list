let completed_btn = document.getElementById("completed_btn");
completed_btn.addEventListener("click", hidden_shown_completed);

let deleted_btn = document.getElementById("deleted_btn");
deleted_btn.addEventListener("click", hidden_shown_deleted);

function hidden_shown_completed(event) {
    event.preventDefault();
    let completed_div = document.getElementById("completed_div");
    
    if (completed_div.className == 'hidden'){
        completed_div.className = 'shown';
    } else if (completed_div.className == 'shown'){
        completed_div.className = 'hidden';
      }
}

function hidden_shown_deleted(event) {
    event.preventDefault();
    let deleted_div = document.getElementById("deleted_div");
    
    if (deleted_div.className == 'hidden'){
        deleted_div.className = 'shown';
    } else if (deleted_div.className == 'shown'){
        deleted_div.className = 'hidden';
      }
}