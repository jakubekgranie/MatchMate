/**
 * The profile page functionality.
 * @see MatchMate/app/resources/views/session/profile.blade.php
 */

const bindings = ["weight", "age", "height", "name", "surname"],
      svgStyles = ["oklch(70.7% 0.022 261.325)", "oklch(84.1% 0.238 128.85)"], // gray, lime
      commitLockedStyles = ["bg-gray-800", "text-gray-300", "hover:bg-gray-700", "focus-visible:outline-gray-700"], // global due to multiple usages
      commitUnlockedStyles = ["bg-lime-950", "text-lime-400", "hover:bg-lime-900", "focus-visible:outline-lime-400"];
let elements,
    validationErrorStates = [false, false, false, false, false],
    archive = [];

function classToggler(element, classArray, classArray2){
    element.classList.add(...classArray);
    element.classList.remove(...classArray2);
}
/**
 * **`buttonLocker()`**
 *
 * This function **disables the submission button** and **adjusts its styling** based on whether the changes provided by the user are valid.
 * Partially relies on `livePreview()`.
 *
 * **Note:** this function **does not** serve as a data validator; see the php components for further insights.
 *
 * @return {void}.
 * @see `livePreview()`
 * @see respective php components
 */
function buttonLocker(){
    const button = document.getElementById("submission"),
          svgIcon = document.getElementById("svgIconText");
    if(validationErrorStates.includes(true)) {
        button.disabled = true;
        classToggler(button, commitLockedStyles, commitUnlockedStyles);
        svgIcon.style.fill = svgStyles[0];
        return;
    }
    let oneOrMoreChanged = false;
    for(let i = 0; i < elements.length; i++) {
        for(let j = 0; j < bindings.length; j++)
            if (elements[i].id === bindings[j] && elements[i].value !== archive[j] && elements[i].value !== "")
                oneOrMoreChanged = true;
    }

    if(oneOrMoreChanged) {
        button.disabled = false;
        classToggler(button, commitUnlockedStyles, commitLockedStyles);
        svgIcon.style.fill = svgStyles[1];
    }
    else{
        button.disabled = true;
        classToggler(button, commitLockedStyles, commitUnlockedStyles);
        svgIcon.style.fill = svgStyles[0];
    }
}
/**
 * **`addSuffix()`**
 *
 * This function **determines a suitable value suffix** based on the provided target id and, sometimes, the given age.
 *
 * @param {string} targetId Required. The target element's identifier used for determining the type of the suffix to be returned.
 * @param {number} age Optional. Used for advanced operations with age suffixes.
 *
 * @return {string} The suffix.
 * @see `livePreview()`
 */
function addSuffix(targetId, age){
    switch(targetId){
        case "weight":
            return "kg";
        case "age":
            const truncated = age % 10;
            if(age % 100 > 20 && truncated > 1 && truncated < 5)
                return "lata";
            else
                return "lat";
        case "height":
            return "cm";
    }
    return "";
}
function raiseError(targetId, errorMessage){
    const errorContainer = document.getElementById(`${targetId}_error`);
    errorContainer.innerHTML = errorMessage;
    errorContainer.classList.remove("hidden");
}
/**
 * **`livePreview()`**
 *
 * This function **automatically displays the user's foreseen profile changes** on the displayed profile card (if valid).
 * Also, **supplies `buttonLocker()` with the validation results** via  `validationErrorStates`.
 *
 * @return {void}
 * @see `buttonLocker()`
 */
function livePreview() {
    for(let i = 0; i < elements.length; i++)
        elements[i].addEventListener('input', () => {
            const target = elements[i];
            for (let j = 0; j < bindings.length; j++)
                if (target.id === bindings[j]) {
                    const profileTarget = document.getElementById(`user_${target.id}`);
                    // Roll back to current values.
                    if(target.value === "") {
                        profileTarget.innerHTML = archive[j];
                        document.getElementById(`${target.id}_error`).classList.add("hidden");
                        validationErrorStates[j] = false;
                        buttonLocker();
                    }
                    // Validate new values and preview them.
                    else {
                        let valueOf = target.value,
                            errorMessage;
                        if(typeof valueOf === "string"){
                            valueOf = valueOf.replace(/\s+/g, ' ').trim(); // Remove surrounding and internally repeating whitespaces.
                            if(valueOf !== target.value) // Replace the input text so the user doesn't exhaust their length.
                                target.value = valueOf;
                            if(valueOf.length === 0) {
                                validationErrorStates[j] = true; // Kill further execution if the value consisted only of whitespaces.
                                buttonLocker();
                                return;
                            }
                        }
                        // Detect and note anomalies.
                        switch (target.id) {
                            case "weight":
                                if (valueOf > 300 || valueOf < 20)
                                    errorMessage = "Skontaktuj się z Obsługą Klienta.";
                                break;
                            case "age":
                                if (valueOf > 120 || valueOf < 18) { // 18 years old
                                    if(valueOf > 120)
                                        errorMessage = "To zbyt wiele lat.";
                                    else
                                        errorMessage = "To zbyt mało lat.";
                                }
                                break;
                            case "height":
                                if (valueOf > 272 || valueOf < 55) // the tallest && the shortest human heights recorded
                                    errorMessage = "Skontaktuj się z Obsługą Klienta.";
                                break;
                        }
                        // Notify the user of discrepancies.
                        if(errorMessage) {
                            validationErrorStates[j] = true;
                            raiseError(target.id, errorMessage);
                        }
                        // Remove the error lock and preview the value.
                        else {
                            validationErrorStates[j] = false;
                            document.getElementById(`${target.id}_error`).classList.add("hidden");
                            document.getElementById(`user_${target.id}`).innerHTML = `${valueOf} ${addSuffix(target.id, valueOf)}`;
                        }
                        buttonLocker();
                    }
                }
        });
}

function dragAndDropSetup(){
    const inputIds = ["pfp", "banner"];
    inputIds.forEach((inputId, i) => {
        const element = document.getElementById(`dnd_${inputId}`),
              hoverClasses = [["bg-gray-700", "outline-gray-300"], ["bg-lime-900", "outline-lime-400"]],
              nonHoverClasses = [["bg-gray-800", "outline-gray-500", "hover:outline-gray-300", "hover:bg-gray-700"], ["bg-lime-950", "outline-lime-400", "hover:bg-lime-900"]];
        let collectionIndex = [0, 0];

        element.addEventListener("dragenter", () => {
            console.log("dragenter");
            classToggler(element, hoverClasses[collectionIndex[i]], nonHoverClasses[collectionIndex[i]]);
        });
        element.addEventListener("dragleave", () => {
            classToggler(element, nonHoverClasses[collectionIndex[i]], hoverClasses[collectionIndex[i]]);
        });
        element.addEventListener("dragover", (e) => e.preventDefault());
        element.addEventListener("drop", (e) => {
            e.preventDefault();
            classToggler(element, nonHoverClasses[collectionIndex[i]], hoverClasses[collectionIndex[i]]);
        });
        document.getElementById(inputId).addEventListener("change", () => {
            if(collectionIndex[i] === 0) {
                classToggler(element, nonHoverClasses[1], hoverClasses[0].concat(nonHoverClasses[0]));
                document.getElementById(`svg${inputIds[i].charAt(0).toUpperCase() + inputIds[i].slice(1)}`).style.fill = svgStyles[1];
                collectionIndex[i] = 1;
            }
        });
        element.addEventListener("click", () => document.getElementById(inputId).click());
    })
}

/**
 * **`initiate()`**
 *
 * This function accepts *no parameters*.
 *
 * This function performs crucial operations **needed by the script to start and function properly**.
 *
 * @return {void}
 */
function initiate(){
    elements = document.querySelectorAll("#profileForm input:not([type=hidden])");
    for (let i = 0; i < bindings.length; i++)
        archive.push(document.getElementById(`user_${bindings[i]}`).innerHTML);
    livePreview();
    dragAndDropSetup();
}
document.addEventListener("DOMContentLoaded", initiate);
