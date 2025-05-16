/**
 * The **notification maintenance** script.
 *
 * **`notify()`**
 *
 * **Enables and prepares the notification**, eventually **removing** it as well.
 *
 * It is supposed to be **executed inline**, via **Blade**.
 *
 * @param {string} title - Required. The context of the notification.
 * @param {number} theme - Optional. Specifies the color scheme and icon of the notification.
 */
function notify(title, theme = 0){
    const notification = document.getElementById("notification");
    notification.addEventListener("animationend", () => notification.remove());
    document.getElementById("notification_feed").innerHTML = title;
    switch (theme) {
        //
    }
}
