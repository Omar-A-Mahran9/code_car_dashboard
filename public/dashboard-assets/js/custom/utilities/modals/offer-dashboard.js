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
        (t = document.querySelector("#kt_create_account_stepper")),
        (i = t.querySelector("#kt_create_account_form")),
        (o = t.querySelector('[data-kt-stepper-action="submit"]')),
        (s = t.querySelector('[data-kt-stepper-action="next"]')),
        (r = new KTStepper(t)).on("kt.stepper.changed", function (e) {
          5 === r.getCurrentStepIndex()
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

          formData.append("step", currentStepIndex);
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
              if (data.data.length !== 0 && currentStepIndex == 3) {
                const offersContainer =
                  document.getElementById("offers-container");
                const offers = data.data;
                clearOffersContainer();
                // Loop through each offer and create HTML elements
                offers.forEach((offer, index) => {
                  // Create radio button and label elements
                  const radioButton = document.createElement("input");
                  radioButton.setAttribute("type", "radio");
                  radioButton.setAttribute("class", "btn-check");
                  radioButton.setAttribute("name", "bank_offer_id");
                  radioButton.setAttribute("value", offer.OfferName.id);
                  radioButton.setAttribute("id", `radio_${index}`);
                  if (index === 0) {
                    radioButton.setAttribute("checked", "checked");
                  }

                  const label = document.createElement("label");
                  label.setAttribute(
                    "class",
                    "btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center h-100"
                  );
                  label.setAttribute("for", `radio_${index}`);

                  // Construct the inner HTML content using template literals
                  label.innerHTML = `
         <div class="card border-0 shadow-sm mb-4">
  <div class="card-body p-4">
    <div class="d-flex align-items-center mb-4 text-start ">
        <i class="fa fa-tag fs-2hx text-success me-3"></i>
              <h6 class="mb-0 fw-bold">${offer.OfferName.id}</h6>

      <h6 class="mb-0 fw-bold">${offer.OfferName.title}</h6>
    </div>
      <div class="row text-start justify-content-between">
        <div class="col-6 text-muted">${__("Monthly installment")}</div>
        <div class="col-6 text-end fw-bold">${
          offer.monthly_installment
        } <span>${__("S.R")}</span></div>
      </div>
    <div class="mb-3 text-start">
      <hr style="border:2px solid black">
      <div class="row mb-2">
        <div class="col-6 text-muted">${__("Price")}</div>
        <div class="col-6 text-end fw-bold">${
          offer.car.price_after_tax
        } <span>${__("S.R")}</span></div>
      </div>
      <div class="row mb-2">
        <div class="col-6 text-muted">${__("The first installment")}</div>
        <div class="col-6 text-end fw-bold">${
          offer.firs_installment
        } <span>${__("S.R")}</span></div>
      </div>
      <div class="row mb-2">
        <div class="col-6 text-muted">${__("The last installment")}</div>
        <div class="col-6 text-end fw-bold">${
          offer.last_installment
        } <span>${__("S.R")}</span></div>
      </div>
      <div class="row mb-2">
        <div class="col-6 text-muted">${__("Years")}</div>
        <div class="col-6 text-end fw-bold">${offer.years} <span> ${__(
                    "Years"
                  )}</span></div>
      </div>
      <div class="row mb-2">
        <div class="col-6 text-muted">${__("Administrative fees")}</div>
        <div class="col-6 text-end fw-bold">${
          offer.sectorAdministrative_fees
        } <span>${__("S.R")}</span></div>
      </div>
    </div>
  </div>
</div>

       `;

                  // Append radio button and label to the offers container
                  offersContainer
                    .appendChild(document.createElement("div"))
                    .appendChild(radioButton);
                  offersContainer.lastChild.appendChild(label);
                });
              }
              if (data.data.length !== 0 && currentStepIndex == 4) {
                const orderDetails = data.data;
                const carDetailsContainer =
                  document.getElementById("details-container");

                const carDetailsHtml = `
                    <div>
                        <h4>${__("car details")}</h4>
                    </div>
                    <div class="bg-light p-3 rounded-3 w-100 mt-1">
                        <!--begin::Profile-->
                        <div class="d-flex gap-7 align-items-center">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-circle symbol-100px">
                                <img src="${
                                  orderDetails.car.main_image
                                }" alt="image" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Contact details-->
                            <div class="d-flex flex-column gap-2">
                                <!--begin::Name-->
                                <h3 class="mb-0">${orderDetails.car.title}</h3>
                                <!--end::Name-->
                                <!--begin::Details-->
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ki-outline ki-sms fs-2"></i>
                                    <a href="#" class="text-muted text-hover-primary">${
                                      orderDetails.car.brand.title
                                    } - ${orderDetails.car.model.title} - ${
                  orderDetails.car.year
                }</a>
                                </div>
                                <!--end::Details-->
                            </div>
                            <!--end::Contact details-->
                        </div>
                        <!--end::Profile-->
                    </div>
                    <div class="mt-5">
                        <h4>${__("Offer details")}</h4>
                    </div>
         <div class="bg-light p-3 rounded-3 w-100 mt-1">
        <!--begin::Profile-->
        <div class="d-flex gap-7 align-items-center mb-3">
            <!--begin::Contact details-->
            <div class="d-flex flex-column gap-2">
                <!--begin::Name-->
                <h3 class="mb-0 fw-bold" style="font-weight:bold !important;">${
                  orderDetails.OfferName.title
                }</h3>
                <!--end::Name-->
                <!--begin::Details-->
                <div class="d-flex align-items-center gap-2 mt-3">
                    <div class="fw-bold">${__("monthly installment")}</div>
                    <div class='fs-4 fw-bold '>${
                      orderDetails.monthly_installment
                    }  <span>${__("S.R")}</span></div>
                </div>
                <!--end::Details-->
            </div>
            <!--end::Contact details-->
        </div>
        <!--end::Profile-->

        <!--begin::Installments-->
        <div class="d-flex flex-column gap-2 p-4">
            <div class="d-flex justify-content-between">
                <div>${__("first installment")}</div>
                <div>${orderDetails.firs_installment} <span>${__(
                  "S.R"
                )}</span> </div>
            </div>
            <div class="d-flex justify-content-between">
                <div>${__("last installment")}</div>
                <div>${orderDetails.last_installment}  <span>${__(
                  "S.R"
                )}</span></div>

            </div>
            <div class="d-flex justify-content-between">
                <div>${__("administrative fees")}</div>
                <div>${orderDetails.sectorAdministrative_fees} <span>${__(
                  "S.R"
                )}</span></div>
            </div>
            <div class="d-flex justify-content-between">
                <div>${__("years")}</div>
                <div>${orderDetails.years} </div>
            </div>
 
        </div>
        <!--end::Installments-->
    </div>
    
                `;

                carDetailsContainer.innerHTML = carDetailsHtml;
              }
              if (data.data.length == 0 && currentStepIndex == 1) {
                Swal.fire({
                  text: __(
                    "Sorry, this car not found now please choose another one"
                  ),
                  icon: "error",
                  buttonsStyling: !1,
                  confirmButtonText: __("Please try again"),
                  customClass: { confirmButton: "btn btn-primary" },
                });
              } else if (data.data.length == 0 && currentStepIndex == 3) {
                Swal.fire({
                  text: __("Sorry, Not found offers for you now"),
                  icon: "error",
                  buttonsStyling: !1,
                  confirmButtonText: __("Please try again"),
                  customClass: { confirmButton: "btn btn-primary" },
                });
              } else {
                // Reference to the container where offers will be appended

                e.goNext();
              }
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
          formData.append("step", 5);
          formData.append("type", "individual");

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
  for (let [key, messages] of Object.entries(errors)) {
    let errorElement = document.getElementById(key);

    if (errorElement) {
      errorElement.innerHTML = messages.join("<br>");
      errorElement.style.display = "block";
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
