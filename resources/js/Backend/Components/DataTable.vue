<template>
  <div>
    <table id="mytable" :class="class_str">
      <thead>
        <slot name="header"></slot>
      </thead>
      <tbody></tbody>
    </table>
  </div>
  <!-- <table :id="id" :class="class_str" cellspacing="0" width="100%">
      <thead>
        <slot name="header"></slot>
      </thead>
      <tbody class="table-sm"></tbody>
    </table> -->
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
export default {
  props: {
    id: {
      type: String,
      default: "mytable",
      required: true,
    },
    class_str: {
      type: String,
      default: "display table table-striped table-hover",
      required: false,
    },
    url: {
      type: String,
      required: true,
    },
    reOrderUrl: {
      type: String,
      default:null,
      required: false,
    },
    method: {
      type: String,
      default: function () {
        // return 'POST'
        return "GET";
      },
      required: false,
    },
    columns: {
      type: Array,
      required: true,
    },
    columnDefs: {
      default: function () {
        return [];
      },
      type: Array,
      required: false,
    },
    order: {
      default: function () {
        return [];
      },
      type: Array,
      required: false,
    },
    buttons: {
      default: function () {
        return [];
      },
      type: Array,
    },
    displayLength: {
      type: Number,
      default: function () {
        return 25;
      },
    },
    reload: {
      type: Boolean,
      default: function () {
        return false;
      },
    },
    lengthMenu: {
      type: Array,
      default: function () {
        return [
          [25, 50, 100, 500],
          ["25 rows", "50 rows", "100 rows", "500 rows"],
        ];
      },
    },
    submitData: {
      type: Object,
      default: function () {
        return {};
      },
    },
  },
  data() {
    return {
      pageLength:25,
    };
  },
  mounted() {
    this.datatable();
  },
  watch: {
    submitData(newValue, oldValue) {
      console.log(`Count changed from ${oldValue} to ${newValue}`);
    },
  },
  methods: {
    datatable() {
      var self = this;
      var csrf_token = this.$page.props.csrf_token;
      var table = $("#" + this.id).DataTable({
        dom: "Blfr<'box table-responsive text-nowrap drag't>ip",
        buttons: this.buttons,
        processing: true,
        serverSide: true,
        retrieve: true,
        aaSorting: [],
        ajax: {
          url: this.url,
          method: this.method,
          data: function (d) {
            d._token = csrf_token;
            for (const key in self.submitData) {
              d[key] = self.submitData[key];
            }
            //  d.length = 25;
          },
        },
        order: this.order,
        columnDefs: this.columnDefs,
        columns: this.columns,
        lengthMenu: this.lengthMenu,
        displayLength: this.displayLength,
        // rowReorder: {
        //   selector: "tr",
        //   dataSrc: "position",
        // },
        select: {
          style: "multi",
          selector: "td div.item-check input[type=checkbox]",
        },

        initComplete: function () {
          var mx = 0;
          $(".drag").on({
            mousemove: function (e) {
              var mx2 = e.pageX - this.offsetLeft;
              if (mx) this.scrollLeft = this.sx + mx - mx2;
            },
            mousedown: function (e) {
              this.sx = this.scrollLeft;
              mx = e.pageX - this.offsetLeft;
            },
          });
          $(document).on("mouseup", function () {
            mx = 0;
          });
        },
      });
      $(".dt-buttons").addClass("float-right");
      $(".dataTables_filter").addClass("float-left");
      $("#mytable_processing").addClass("mg-top-26s")
        .html(`<div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                    </div>`);
      $(
        ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
      ).addClass("btn btn-main mr-1 btn-sm");

      $("#" + this.id).on('length.dt', function(e, settings, len) {
          // console.log('Page length changed to: ' + settings);
          self.pageLength = len;
      });

      $("#mytable tbody").sortable({
        items: "tr",
        handle:".change-order",
        cursor: "move",
        opacity: 0.6,
        update: function () {
          var tableInfo = table.page.info();
          self.reOrderColumns(tableInfo);
        },
      });
    },
    reloadDatatable() {
      var table = $("#" + this.id).DataTable();
      table.ajax.reload();
    },
    reOrderColumns(tableInfo) {
      var self = this;
      var rStart = tableInfo.start;
      var rEnd = tableInfo.end;
      var order = [];
      $('tr div.change-order').each(function(i, el) {
        order.push({
          id: $(this).attr('data-item-id'),
          position:rStart+(i+1)
        });
      });
      if(self.reOrderUrl) {
        Inertia.post(self.reOrderUrl, {rows:order}, {
          preserveScroll: true,
          onSuccess: () => {
            self.reloadDatatable();
          }
        });
      }
    }
  },
};
</script>

<style>
</style>
