<template>
  <section class="term-block">
    <div class="term-block__header">
      <h3>{{ $t("nbds_lang.table_category_others") }}</h3>
    </div>
    <div class="term-block__terms">
      <table class="table table--compact">
        <tbody>
          <tr v-if="value.desig_type">
            <td dir="auto">
              {{ $t("nbds_lang.protected_areas.desig_type_other") }}
            </td>
            <td dir="auto">
              {{ $t("nbds_lang." + value.desig_type) }}
            </td>
          </tr>
          <tr v-if="value.iucncat && value.iucncat != 'Not Reported'">
            <td dir="auto">
              {{ $t("nbds_lang.protected_areas.iucncat") }}
            </td>
            <td dir="auto">{{ value.iucncat }}</td>
          </tr>
          <tr v-if="value.rep_area">
            <td dir="auto">
              {{ $t("nbds_lang.protected_areas.rep_area") }}
            </td>
            <td dir="auto">{{ $n(value.rep_area) }} (ha)</td>
          </tr>
          <tr v-if="value.status">
            <td dir="auto">
              {{ $t("nbds_lang.protected_areas.status") }}
            </td>
            <td dir="auto">
              {{ value.status }}
            </td>
          </tr>
          <tr v-if="value.status_year">
            <td dir="auto">
              {{ $t("nbds_lang.protected_areas.status_year") }}
            </td>
            <td dir="auto">{{ value.status_year }}</td>
          </tr>
          <tr v-if="value.gov_type">
            <td dir="auto">
              {{ $t("nbds_lang.protected_areas.gov_type") }}
            </td>
            <td dir="auto">{{ value.gov_type }}</td>
          </tr>
          <tr>
            <td dir="auto">
              {{ $t("nbds_lang.protected_areas.international_criteria") }}
            </td>
            <td dir="auto">
              {{
                value.international_criteria
                  ? value.international_criteria
                  : $t("nbds_lang.no_data")
              }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>

<script>
export default {
  props: ["value"],
  data: function () {
    return {};
  },
  created() {},
};
</script>

<style scoped>
.inline {
  margin: 0;
}
.inline > div {
  margin-bottom: 5px;
}
.small {
  font-size: 85%;
  padding: 15px;
  margin-bottom: 30px;
}
</style>
