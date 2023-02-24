<template>
  <div class="d-flex align-center full-width">
    <div class="flex-grow-1 pr-2">
      <input
        v-model="year"
        type="number"
        class="full-width form-control"
        min="1970"
        :max="maxYear"
        step="1"
        @input="changeYear"
      />
    </div>
    <div class="flex-grow-1 pr-2" style="min-width: 50px;">
      <select
        v-model="month"
        class="full-width form-control"
        @change="changeMonth"
      >
        <option :value="n" v-for="n in maxMonth" :key="n">{{ n }}</option>
      </select>
    </div>
    <div class="flex-grow-1 pr-2" style="min-width: 50px;">
      <select v-model="day" class="full-width form-control" @change="changeDay">
        <option :value="n" v-for="n in maxDay" :key="n">{{ n }}</option>
      </select>
    </div>
    <div class="flex-grow-0" style="min-width: 50px;">
      <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :return-value.sync="date"
        offset-y
        min-width="290px"
      >
        <template v-slot:activator="{ on }">
          <v-icon small v-on="on">fa-calendar-times</v-icon>
        </template>
        <v-date-picker
          :min="min"
          :max="max"
          :allowed-dates="allowedDates"
          v-model="selectDate"
          no-title
          scrollable
        >
          <v-spacer></v-spacer>
          <v-btn @click="close" flat>{{ $t("nbds_lang.value_no") }}</v-btn>
          <v-btn color="primary" class="ml-2" @click="saveDate" flat>{{
            $t("nbds_lang.confirm")
          }}</v-btn>
        </v-date-picker>
      </v-menu>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    //format yyyy-mm-dd
    value: {},
    allowedDates: {},
    min: {},
    max: {},
  },
  data: () => ({
    menu: false,
    date: "",
    selectDate: "",
    day: 1,
    month: 1,
    year: 1970,
    maxDay: 31,
  }),
  watch: {
    value: {
      handler(value) {
        this.date = value;
        this.selectDate = value;
        if (value) {
          let dates = value.split("-");
          this.year = parseInt(dates[0]);
          this.month = parseInt(dates[1]);
          this.day = parseInt(dates[2]);
          this.maxDay = new Date(this.year, this.month, 0).getDate();
        }
      },
      immediate: true,
    },
    menu: {
      handler() {
        this.selectDate = this.date;
      },
    },
  },
  mounted() {
    if (!this.value) {
      this.currentdate = "1970-01-01";
    }
  },
  computed: {
    maxYear() {
      return new Date().getFullYear();
    },
    maxMonth() {
      return 12;
    },
    currentdate: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
  },
  methods: {
    close() {
      this.menu = false;
    },
    clearDate() {
      this.currentdate = "";
    },
    saveDate() {
      this.$refs.menu.save(this.selectDate);
      this.date = this.selectDate;
      this.currentdate = this.selectDate;
    },
    changeYear() {
      this.day = 1;
      this.month = 1;
      this.changeDay();
    },
    changeMonth() {
      this.day = 1;
      this.changeDay();
    },
    changeDay() {
      if (this.year && this.month && this.day) {
        "".padStart();
        let date = `${this.year}-${(this.month + "").padStart(2, 0)}-${(
          this.day + ""
        ).padStart(2, 0)}`;
        this.date = date;
        this.currentdate = date;
      }
    },
  },
};

function daysInMonth(month, year) {
  return new Date(year, month, 0).getDate();
}
</script>

<style scoped></style>
