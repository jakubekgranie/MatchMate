/**
 * The profile page functionality.
 * @see app/resources/views/session/profile.blade.php
 */
let elements;
function buttonLocker() {
    const lockedStyles = ["bg-gray-800", "text-gray-300", "hover:bg-gray-700"],
          unlockedStyles = ["bg-lime-950", "text-lime-400", "hover:bg-lime-900"],
          svgStyles = ["oklch(70.7% 0.022 261.325)", "oklch(84.1% 0.238 128.85)"], // gray, lime
          button = document.getElementById("submission"),
          svgIcon = document.getElementById("svgIcon");
    for(let i = 0; i < elements.length; i++)
        elements[i].addEventListener('change', () => {
            for(let i = 0; i < elements.length; i++)
                if(elements[i].value === "") {
                    button.disabled = true;
                    button.classList.remove(...unlockedStyles);
                    button.classList.add(...lockedStyles);
                    svgIcon.style.fill = svgStyles[0];
                    return;
                }
            button.classList.remove(...lockedStyles);
            button.classList.add(...unlockedStyles);
            svgIcon.style.fill = svgStyles[1];
            document.getElementById("submission").disabled = false;
        });
}
function livePreview() {
    const bindings = ["weight", "age", "height"];
    let archive = [];
    for (let i = 0; i < bindings.length; i++)
        archive.push(document.getElementById(`user_${bindings[i]}`).innerHTML);

    bindings.push("name", "surname");
    archive.push(...document.getElementById("user_name").innerHTML.split(" "));
    for(let i = 0; i < elements.length; i++)
        elements[i].addEventListener('change', () => {
            let flag;
            const target = elements[i];
            if(target.value === "") {
                flag = false;
                const userName = document.getElementById("user_name")
                if(target.id === "name")
                    userName.innerHTML = `${archive[3]} ${document.getElementById("surname").value}`
                else if(target.id === "surname")
                    userName.innerHTML = `${document.getElementById("name").value} ${archive[4]}`
                else {
                    let binding;
                    for (let i = 0; i < bindings.length; i++)
                        if (target.id === bindings[i])
                            binding = bindings[i];
                    /*
                            $truncatedAge = $user->age % 10;
                    if($truncatedAge == 1)
                        $suffix = 'rok';
                    if($user->age % 100 > 20 && $truncatedAge > 1 && $truncatedAge < 5)
                        $suffix = 'lata';
                    else
                        $suffix = 'lat';
                            */
                    const valueOf = document.getElementById(binding).value, profileTarget = document.getElementById(`user_${binding}`).innerHTML;
                    switch (binding){
                        case "weight":
                            if(valueOf > 300)
                                return;
                            // kontynuuj
                    }
                }
            }
            else {
                flag = true;
                for (let i = 0; i < elements.length; i++)
                    if (target.id === bindings[i])
                        document.getElementById(`user_${target.id}`).innerHTML = target.value; console.log(target.value, document.getElementById(`user_${target.id}`))
            }
        });
}
function initiate(){
    elements = document.querySelectorAll("#profileForm *:not(button)");
    buttonLocker();
    livePreview();
}
document.addEventListener("DOMContentLoaded", initiate);
