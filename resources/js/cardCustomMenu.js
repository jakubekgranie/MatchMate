/*
function initiate(){
    const cards = document.querySelectorAll(".profileCard:not(.notClickable)"),
          cardContextMenu = document.getElementById("cardContextMenu");
    for(let i = 0; i < cards.length; i++)
        cards[i].addEventListener("contextmenu", (e) => {
            if(document.getElementById("cardContextMenu"))
                document.getElementById("cardContextMenu").remove()
            const newMenu = cardContextMenu.cloneNode(true);
            document.getElementsByTagName("main")[0].appendChild(newMenu);
            newMenu.style = `top: ${e.clientY}px; left: ${e.clientX}px;`;
            e.preventDefault();
        });
    cardContextMenu.classList.remove("hidden");
    cardContextMenu.remove();
}
document.addEventListener("DOMContentLoaded", initiate);
*/
