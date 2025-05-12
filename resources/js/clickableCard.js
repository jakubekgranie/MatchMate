function initiate(){
    const cards = document.querySelectorAll(".profileCard");
    for(let i = 0; i < cards.length; i++)
        cards[i].addEventListener("click", () => cards[i].children[1].classList.toggle("hidden"));
}

document.addEventListener("DOMContentLoaded", initiate);
