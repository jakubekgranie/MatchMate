/**
 * The profile page functionality.
 * @see MatchMate/app/resources/views/session/profile.blade.php
 */

const svgStyles = ["oklch(70.7% 0.022 261.325)", "oklch(84.1% 0.238 128.85)"]; // gray, lime

let forms = [],
    buttons = [],
    bannedInputs = ["password_confirmation"],
    validationErrorStates = [],
    archive = [],
    rawPaths;

/**
 * **`classToggler()`**
 *
 * **Toggles classes** on the target element.
 *
 * @param {HTMLElement} element - Required. The target element.
 * @param {string[]} classArray - Required. The styles to be applied.
 * @param {string[]} classArray2 - Required. The styles to be removed.
 */
function classToggler(element, classArray, classArray2){
    element.classList.add(...classArray);
    element.classList.remove(...classArray2);
}

/**
 * **`changeButtonLock()`**
 *
 * **Disables or enables a button** both stylistically and directly.
 *
 * @param {HTMLButtonElement} button - Required. The target button.
 * @param lock - Optional. Specifies the action taken.
 */
function changeButtonLock(button, lock = true){
    const commitLockedStyles = ["bg-gray-800", "text-gray-300", "hover:bg-gray-700", "focus-visible:outline-gray-700"],
          commitUnlockedStyles = ["bg-lime-950", "text-lime-400", "hover:bg-lime-900", "focus-visible:outline-lime-700"],
          svgIcon = document.querySelector(`#${button.id} svg`);
    if(lock && !button.disabled){
        button.disabled = true;
        classToggler(button, commitLockedStyles, commitUnlockedStyles);
        svgIcon.style.fill = svgStyles[0];
    }
    if(!lock && button.disabled){
        button.disabled = false;
        classToggler(button, commitUnlockedStyles, commitLockedStyles);
        svgIcon.style.fill = svgStyles[1];
    }
}

/**
 * **`buttonLocker()`**
 *
 * This function **disables the submission button** and **adjusts its styling** based on whether the changes provided by the user are valid.
 *
 * Partially relies on `livePreview()` and executes the changes via `changeButtonLock()`.
 *
 * **Note:** this function **does not** serve as a *de facto* data validator; see the php components for further insights.
 *
 * @param {number} formId - Required. Used for identifying elements.
 * @return {void}
 * @see `livePreview()`
 * @see `changeButtonLock()`
 * @see respective php components
 */
function buttonLocker(formId){
    const button = buttons[formId];
    if (validationErrorStates[formId].includes(true)) {
        changeButtonLock(button, true);
        return;
    }
    for (let j = 0; j < forms[formId].length; j++)
        for (let k = 0; k < forms[formId].length; k++)
            if (forms[formId][j].id === forms[formId][k].id && forms[formId][j].value !== archive[k] && forms[formId][j].value !== "") {
                changeButtonLock(button, false);
                return;
            }
    changeButtonLock(button, true);
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
    forms.forEach((formInputs, formId) => {
        formInputs.forEach(formInput => {
            if(!bannedInputs.includes(formInput.id))
                formInput.addEventListener('input', () => {
                    const target = formInput;
                    for (let j = 0; j < formInputs.length; j++)
                        if (target.id === formInputs[j].id) {
                            const profileTarget = document.getElementById(`user_${target.id}`);
                            // Roll back to current values.
                            if (target.value === "") {
                                if(formId === 0) // profileForm only
                                    profileTarget.innerHTML = archive[j];
                                document.getElementById(`${target.id}_error`).classList.add("hidden");
                                validationErrorStates[formId][j] = false;
                                buttonLocker(formId);
                            }
                            // Validate new values and preview them.
                            else {
                                let valueOf = target.value,
                                    errorMessage;
                                if (typeof valueOf === "string") {
                                    valueOf = valueOf.replace(/\s+/g, ' ').trim(); // Remove surrounding and internally repeating whitespaces.
                                    if (valueOf !== target.value) // Replace the input text so the user doesn't exhaust their length.
                                        target.value = valueOf;
                                    if (valueOf.length === 0) {
                                        validationErrorStates[formId][j] = true; // Kill further execution if the value consisted only of whitespaces.
                                        buttonLocker(formId);
                                        return;
                                    }
                                }
                                // Detect and note anomalies.
                                switch (target.id) {
                                    case "name":
                                        if (valueOf.length < 2)
                                            errorMessage = "To imię jest zbyt krótkie."
                                        else if (valueOf.length > 14)
                                            errorMessage = "To imię jest zbyt długie."
                                        else if (!valueOf.match(/^[A-Za-z]+$/))
                                            errorMessage = "Wykryto niedozwolone znaki.";
                                        else if (!valueOf.match(/^[A-Z][a-z]+$/))
                                            errorMessage = "Niepoprawna wysokość liter.";
                                        break;
                                    case "surname":
                                        if (valueOf.length < 2)
                                            errorMessage = "To nazwisko jest zbyt krótkie."
                                        else if (valueOf.length > 35)
                                            errorMessage = "To nzawisko jest zbyt długie."
                                        else if (!valueOf.match(/^[A-Za-z]+$/))
                                            errorMessage = "Wykryto niedozwolone znaki.";
                                        else if (!valueOf.match(/^[A-Z][a-z]+$/))
                                            errorMessage = "Niepoprawna wysokość liter.";
                                        break;
                                    case "weight":
                                        if (valueOf > 300 || valueOf < 20)
                                            errorMessage = "Skontaktuj się z Obsługą Klienta.";
                                        break;
                                    case "age":
                                        if (valueOf > 120 || valueOf < 18) { // 18 years old
                                            if (valueOf > 120)
                                                errorMessage = "To zbyt wiele lat.";
                                            else
                                                errorMessage = "To zbyt mało lat.";
                                        }
                                        break;
                                    case "height":
                                        if (valueOf > 272 || valueOf < 55) // the tallest && the shortest human heights recorded
                                            errorMessage = "Skontaktuj się z Obsługą Klienta.";
                                        break;

                                    /*
                                    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

                                    Source \/
                                    https://stackoverflow.com/questions/46155/how-can-i-validate-an-email-address-in-javascript
                                    Test \/
                                    https://jsfiddle.net/ghvj4gy9/

                                    "redundant" character escapes left for security purposes.
                                    */
                                    case "email":
                                        if(!valueOf.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/))
                                            errorMessage = "Niepoprawny adres e-mail.";
                                        break;
                                    case "password":
                                        if(valueOf.length < 8)
                                            errorMessage = "Hasło powinno zawierać min. 8 znaków";
                                        else if(valueOf.length > 64)
                                            errorMessage = "To hasło jest za długie.";
                                        else if(!valueOf.match(/[A-Za-z]+/) || !valueOf.match(/[A-Z]/) && !valueOf.match(/[a-z]/))
                                            errorMessage = "Hasło powinno zawierać co najmniej jedną małą i dużą literę.";
                                        else if (!valueOf.match(/\d/))
                                            errorMessage = "Hasło powinno zawierać cyfry.";
                                        else if(valueOf.match(/\s/))
                                            errorMessage = "Hasło nie może zawierać spacji."
                                        else if(!valueOf.match(/[\W|_]/))
                                            errorMessage = "Hasło powinno zawierać symbole.";
                                        break
                                    default:
                                        errorMessage = `Spróbuj ponownie później. ID błędu: validation.${target.id}`;
                                }
                                // Notify the user of discrepancies.
                                if (errorMessage) {
                                    validationErrorStates[formId][j] = true;
                                    const errorContainer = document.getElementById(`${target.id}_error`);
                                    errorContainer.innerHTML = errorMessage;
                                    errorContainer.classList.remove("hidden");
                                }
                                // Remove the error lock and preview the value.
                                else {
                                    validationErrorStates[formId][j] = false;
                                    document.getElementById(`${target.id}_error`).classList.add("hidden");
                                    if(document.getElementById(`user_${target.id}`))
                                        document.getElementById(`user_${target.id}`).innerHTML = `${valueOf} ${addSuffix(target.id, valueOf)}`;
                                }
                                buttonLocker(formId);
                            }
                        }
                });
        });
    });
}

const defaultDNDStyling = ["bg-gray-800", "outline-gray-500", "hover:outline-gray-300", "hover:bg-gray-700"],
    activatedStyling = [["bg-lime-900", "outline-lime-400"], ["bg-lime-950", "outline-lime-400", "hover:bg-lime-900"]];

/**
 * **`imageButtonLocker()`**
 *
 * This function serves **as a `dragAndDropSetup()`'s helper (for applying styles)**. Uses `changeButtonLock()` for verdict execution.
 *
 * @param {number} i - Required. Used for determinimg what to compare.
 * @param {boolean} forceLock - Optional. Locks the button regardless for the `i`.
 * @return {void}
 * @see `dragAndDropSetup()`
 * @see `changeButtonLock()`
 */
function imageButtonLocker(i, forceLock = false){
    const button = document.getElementById("imageSubmission"),
          svgIcon = document.getElementById("svgIconImages"),
          ids = ["pfp", "banner"];
    let lock = true;
    if(document.getElementById(ids[i]).value.replace(/^.*[\\/]/, '') !== "" && !forceLock)
        lock = false;
    changeButtonLock(button, svgIcon, lock);
}

/**
 * **`previewImage()`**
 *
 * This function **handles direct image substitution**.
 *
 * @param {Blob} file - Required. Contains the to-be-inserted file data.
 * @param {number} i - Required. Determines the target host element.
 * @see `dragAndDropSetup()`
 */
function previewImage(file, i){
    if(file) {
        const reader = new FileReader();
        reader.onload = () => {
            switch (i) {
                case 0:
                    document.getElementById("user_pfp").src = reader.result;
                    break;
                case 1:
                    document.getElementById("user_banner").style.backgroundImage = `url("${reader.result}")`;
                    break;
            }
        }
        reader.readAsDataURL(file);
    }
}
/**
 * **`dragAndDropSetup()`**
 *
 * This function accepts *no parameters*.
 *
 * This function **enables drag and drop functionality**, offers enhanced native input interaction and reactive styling**.
 *
 * The titular functionality is **disabled** as of now, since you **cannot programmatically set input file values**.
 *
 * @return {void}
 */
function dragAndDropSetup(){

    const inputIds = ["pfp", "banner"];
    let collectionIndex = [0, 0];
    inputIds.forEach((inputId, i) => {
        const element = document.getElementById(`dnd_${inputId}`),
              hoverClasses = [["bg-gray-700", "outline-gray-300"], activatedStyling[0]],
              nonHoverClasses = [defaultDNDStyling, activatedStyling[1]];
        /*
        element.addEventListener("dragenter", () => classToggler(element, hoverClasses[collectionIndex[i]], nonHoverClasses[collectionIndex[i]]));
        element.addEventListener("dragleave", () => classToggler(element, nonHoverClasses[collectionIndex[i]], hoverClasses[collectionIndex[i]]));
        element.addEventListener("dragover", (e) => e.preventDefault());
        element.addEventListener("drop", (e) => {
            e.preventDefault();
            classToggler(element, nonHoverClasses[collectionIndex[i]], hoverClasses[collectionIndex[i]]);
            const file = e.dataTransfer.files[0],
                  allowedExtensions = ["png", "webp", "pjp", "jpg", "pjpeg", "jpeg", "jfif"];
            let allowed = false;
            allowedExtensions.forEach(extension => {
                if (file.name.search(new RegExp(`.${extension}`)))
                    allowed = true;
            });
            if(allowed)
                previewImage(file, i);
            imageButtonLocker(i);
        });
        */
        const trueInput = document.getElementById(inputId);
        element.addEventListener("click", () => trueInput.click());
        trueInput.addEventListener("change", (e) => {
            const svgIcon = document.getElementById(`svg${inputIds[i].charAt(0).toUpperCase() + inputIds[i].slice(1)}`);
            if(collectionIndex[i] === 0) {
                classToggler(element, nonHoverClasses[1], hoverClasses[0].concat(nonHoverClasses[0]));
                svgIcon.style.fill = svgStyles[1];
                collectionIndex[i] = 1;
            }
            else if(trueInput.value === "") {
                classToggler(element, nonHoverClasses[0], hoverClasses[1].concat(nonHoverClasses[1]));
                svgIcon.style.fill = svgStyles[0];
                collectionIndex[i] = 0;
            }
            imageButtonLocker(i);
            previewImage(e.target.files[0], i);
        });
    });
    document.getElementById("imageReset").addEventListener("click", () => {
        document.getElementById("imageForm").reset();
        for(let i = 0; i < inputIds.length; i++) {
            classToggler(document.getElementById(`dnd_${inputIds[i]}`), defaultDNDStyling, activatedStyling[0].concat(activatedStyling[1]));
            document.getElementById(`svg${inputIds[i].charAt(0).toUpperCase() + inputIds[i].slice(1)}`).style.fill = svgStyles[0];
            document.getElementById("user_pfp").src = rawPaths[0];
            document.getElementById("user_banner").style.backgroundImage = rawPaths[1];
            collectionIndex = [0, 0];
        }
        imageButtonLocker(0, true);
    });
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
    ["profileForm", "emailForm", "passwordForm"].forEach(form => {
        forms.push(document.querySelectorAll(`#${form} input:not([type="hidden"])`));
        buttons.push(document.querySelector(`#${form} button[type="submit"]`));
        const validationMatrix = [];
        for(let i = 0; i < forms[forms.length - 1].length; i++)
            validationMatrix.push(false);
        validationErrorStates.push(validationMatrix);
    });

    // profileForm and imageForm only
    for (let i = 0; i < forms.length; i++)
        archive.push(document.getElementById(`user_${forms[0][i].id}`).innerHTML);
    archive.push(document.getElementById("user_banner").style.backgroundImage, document.getElementById("user_pfp").src);

    livePreview();
    rawPaths = [document.getElementById("user_pfp").src, document.getElementById("user_banner").style.backgroundImage];
    dragAndDropSetup();
}
document.addEventListener("DOMContentLoaded", initiate);
