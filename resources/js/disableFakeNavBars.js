document.addEventListener("DOMContentLoaded", () => {
   document.querySelectorAll("#fillerNav a").forEach(link =>
       link.addEventListener("click", (e) => e.preventDefault())
   );document.querySelectorAll("#fillerNav button").forEach(link =>
       link.addEventListener("click", (e) => e.preventDefault())
   );
});
