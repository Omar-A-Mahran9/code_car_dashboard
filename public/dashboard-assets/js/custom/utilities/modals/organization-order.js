"use strict";
var KTCreateAccount = (function () {
  var e,
    t,
    i,
    o,
    s,
    r,
    a = [];
  // Function to clear offers container
  function clearOffersContainer() {
    const offersContainer = document.getElementById("offers-container");
    offersContainer.innerHTML = ""; // Remove all child elements
  }

  return {
    init: function () {
      (e = document.querySelector("#kt_modal_create_account")) &&
        new bootstrap.Modal(e),
        (t = document.querySelector("#kt_create_account_stepper_organization")),
        (i = t.querySelector("#kt_create_account_form_organization")),
        (o = t.querySelector('[data-kt-stepper-action="submit"]')),
        (s = t.querySelector('[data-kt-stepper-action="next"]')),
        (r = new KTStepper(t)).on("kt.stepper.changed", function (e) {
          2 === r.getCurrentStepIndex()
            ? (o.classList.remove("d-none"),
              o.classList.add("d-inline-block"),
              s.classList.add("d-none"))
            : 5 === r.getCurrentStepIndex()
            ? (o.classList.add("d-none"), s.classList.add("d-none"))
            : (o.classList.remove("d-inline-block"),
              o.classList.remove("d-none"),
              s.classList.remove("d-none"));
        }),
        r.on("kt.stepper.next", function (e) {
          console.log("stepper.next");
          var currentStepIndex = r.getCurrentStepIndex();

          var formData = new FormData(i);
          //   formData.append("cars", JSON.stringify(cars));
          formData.append("step", currentStepIndex);
          //   formData.append("type", "individual");
          clearErrors();
          // Send form data to Laravel route using fetch API
          fetch("/dashboard/finance-dash", {
            method: "POST",
            body: formData,
          })
            .then((response) => {
              if (!response.ok) {
                return response.json().then((err) => {
                  handleValidationErrors(err.errors);
                  throw new Error("Network Error");
                });
              }

              return response.json(); // Parse the JSON response
            })
            .then((data) => {
              e.goNext();
            })
            .catch((error) => {
              console.error("Error:", error);
              // Handle error here
            });
          var t = a[e.getCurrentStepIndex() - 1];

          t;
          //   e.goNext();
        }),
        r.on("kt.stepper.previous", function (e) {
          console.log("stepper.previous"), e.goPrevious();
        }),
        o.addEventListener("click", function (e) {
          var formData = new FormData(i);
          formData.append("step", 2);
          formData.append("type", "organization");

          console.log("validated!"), clearErrors();
          // Send form data to Laravel route using fetch API
          fetch("/dashboard/finance-dash", {
            method: "POST",
            body: formData,
          })
            .then((response) => {
              if (!response.ok) {
                return response.json().then((err) => {
                  handleValidationErrors(err.errors);
                  throw new Error("Network Error");
                });
              } else {
                Swal.fire({
                  text: __("Request is submitted successfully"),
                  icon: "success",
                  buttonsStyling: !1,
                  confirmButtonText: __("submit"),
                  customClass: { confirmButton: "btn btn-success" },
                }).then(function () {
                  location.reload(); // Reload the page after clicking the "submit" button in Swal
                });
              }

              return response.json(); // Parse the JSON response
            })
            .then((data) => {
              console.log("fgfggfgffg");
            })
            .catch((error) => {
              console.error("Error:", error);
              // Handle error here
            });
        }),
        $(i.querySelector('[name="card_expiry_month"]')).on(
          "change",
          function () {
            a[3].revalidateField("card_expiry_month");
          }
        ),
        $(i.querySelector('[name="card_expiry_year"]')).on(
          "change",
          function () {
            a[3].revalidateField("card_expiry_year");
          }
        ),
        $(i.querySelector('[name="business_type"]')).on("change", function () {
          a[2].revalidateField("business_type");
        });
    },
  };
})();
// Function to handle validation errors
function handleValidationErrors(errors) {
  // Log errors or display them to the user
  console.error("Validation Errors:", errors);
  // Display errors inside the corresponding invalid-feedback elements

  if (errors.field_number == 0 || errors.field_number) {
    let errorElement = document.getElementById(`error-${errors.field_number}`);
    if (errorElement) {
      errorElement.innerHTML = errors.errors;
      errorElement.style.display = "block";
    }
  } else {
    for (let [key, messages] of Object.entries(errors)) {
      let errorElement = document.getElementById(key);
      let errorElementOrg = document.getElementById(key + "_org");
      if (errorElementOrg) {
        errorElementOrg.innerHTML = messages.join("<br>");
        errorElementOrg.style.display = "block";
      }
      if (errorElement) {
        errorElement.innerHTML = messages.join("<br>");
        errorElement.style.display = "block";
      }
    }
  }
}
function clearErrors() {
  let errorElements = document.querySelectorAll(".invalid-feedback");
  errorElements.forEach((el) => {
    el.innerHTML = "";
    el.style.display = "none"; // Hide the error element
  });
}

KTUtil.onDOMContentLoaded(function () {
  KTCreateAccount.init();
});
