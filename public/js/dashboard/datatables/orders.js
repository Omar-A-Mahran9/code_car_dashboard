"use strict";

// Class definition
let KTDatatable = (function () {
  // Shared variables
  let table;
  let datatable;
  let filter;

  // Private functions
  let initDatatable = function () {
    datatable = $("#kt_datatable").DataTable({
      orderable: false,
      searchDelay: 500,
      processing: true,
      serverSide: true,
      order: [[6, "desc"]], // display records number and ordering type
      stateSave: false,
      select: {
        style: "os",
        selector: "td:first-child",
        className: "row-selected",
      },
      ajax: {
        data: function () {
          let datatable = $("#kt_datatable");
          let info = datatable.DataTable().page.info();
          datatable
            .DataTable()
            .ajax.url(
              `/dashboard/orders?page=${info.page + 1}&per_page=${info.length}`
            );
        },
      },
      columns: [
        { data: "id" },
        { data: "name" },
        { data: "phone" },
        { data: "price" },
        { data: "type" },
        { data: "status_id", name: "status_id" },
        {
          data: "order_details_carselect.payment_type",
          name: "payment_type",
        }, // Make sure this is correct
        { data: "created_at", name: "created_at" },
        { data: "employee.name" },
        { data: "opened_at" },
        // { data: "employee_id" },
        { data: null },
      ],
      columnDefs: [
        {
          targets: 3,
          render: function (data, type, row) {
            if (data) return data + " " + __(currency);
            return "<h1>-</h1>";
          },
        },
        {
          targets: 4,
          render: function (data, type, row) {
            return __(data.replace("_", " "));
          },
        },
        {
          targets: 5,
          render: function (data, type, row) {
            return getStatusObject(data)["name_" + locale];
          },
        },

        {
          targets: 6, // Adjust index based on actual order
          render: function (data, type, row) {
            console.log("Row Data:", row); // Debugging

            if (
              row.order_details_carselect &&
              row.order_details_carselect.payment_type
            ) {
              let paymentType = row.order_details_carselect.payment_type;
              console.log("Payment Type:", paymentType); // Debugging

              return paymentType === "cash"
                ? __("cash order")
                : __("finance calculator");
            }

            return "<h1>-</h1>";
          },
        },
        {
          targets: 8, // Adjust the column index to match the actual column number
          render: function (data, type, row) {
            return row.employee && row.employee.name
              ? row.employee.name
              : "<h1>-</h1>";
          },
        },
        {
          targets: -1,
          data: null,
          render: function (data, type, row) {
            return `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                ${__("Actions")}
                                <span class="svg-icon svg-icon-5 m-0">
                                    <i class="fa fa-angle-down mx-1"></i>
                                </span>
                            </a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">


                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="/dashboard/orders/${
                                      row.id
                                    }" class="menu-link px-3 d-flex justify-content-between" >
                                       <span> ${__("Show")} </span>
                                       <span>  <i class="fa fa-eye text-black-50"></i> </span>
                                    </a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 d-flex justify-content-between delete-row" data-row-id="${
                                      row.id
                                    }" data-type="${__("order")}">
                                       <span> ${__("Delete")} </span>
                                       <span>  <i class="fa fa-trash text-danger"></i> </span>
                                    </a>
                                </div>
                                <!--end::Menu item-->

                            </div>
                            <!--end::Menu-->
                        `;
          },
        },
      ],
    });

    table = datatable.$;

    datatable.on("draw", function () {
      handleDeleteRows();
      handleFilterDatatable();
      KTMenu.createInstances();
    });
  };

  // general search in datatable
  let handleSearchDatatable = () => {
    $("#general-search-inp").keyup(function () {
      datatable.search($(this).val()).draw();
    });
  };

  // Filter Datatable
  let handleFilterDatatable = () => {
    $(".filter-datatable-inp").each((index, element) => {
      $(element).change(function () {
        let columnIndex = $(this).data("filter-index"); // index of the searching column
        datatable.column(columnIndex).search($(this).val()).draw();
      });
    });
  };

  // Delete record
  let handleDeleteRows = () => {
    $(".delete-row").click(function () {
      let rowId = $(this).data("row-id");
      let type = $(this).data("type");

      deleteAlert(type).then(function (result) {
        if (result.value) {
          loadingAlert(__("deleting now ..."));

          $.ajax({
            method: "delete",
            headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/dashboard/orders/" + rowId,
            success: () => {
              setTimeout(() => {
                successAlert(
                  `${
                    __("You have deleted the") +
                    " " +
                    type +
                    " " +
                    __("successfully !")
                  } `
                ).then(function () {
                  datatable.draw();
                });
              }, 1000);
            },
            error: (err) => {
              if (err.hasOwnProperty("responseJSON")) {
                if (err.responseJSON.hasOwnProperty("message")) {
                  errorAlert(err.responseJSON.message);
                }
              }
            },
          });
        } else if (result.dismiss === "cancel") {
          errorAlert(__("was not deleted !"));
        }
      });
    });
  };

  // Public methods
  return {
    init: function () {
      initDatatable();
      handleSearchDatatable();
    },
  };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
  KTDatatable.init();
});
