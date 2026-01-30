document.addEventListener("DOMContentLoaded", () => {
    console.log("Auth page loaded 🔧");

    const inputs = document.querySelectorAll(".auth-input");

    inputs.forEach((input) => {
        input.addEventListener("focus", () => {
            input.parentElement.classList.add("focused");
        });

        input.addEventListener("blur", () => {
            input.parentElement.classList.remove("focused");
        });
    });
});
