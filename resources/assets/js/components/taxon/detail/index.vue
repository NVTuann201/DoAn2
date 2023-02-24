<template>
  <div class="site-wrapper">
    <div class="occurrenceKey page--data">
      <HeaderTaxon v-model="item"></HeaderTaxon>
      <section class="horizontal-stripe--paddingless white-background seperator--b">
        <div class="container--normal">
          <div class="container--desktop">
            <div class="row">
              <div class="col-xs-12" style="padding:0">
                <div class="tabs__wrapper">
                  <nav class="tabs tabs--noBorder">
                    <ul>
                      <li class="tab" :class="[tab == 1 ?'isActive':'']" @click="tab = 1">
                        <a href="#description">
                          {{
                          $t("nbds_lang.table")
                          }}
                        </a>
                      </li>
                      <li
                        v-if="images > 0"
                        class="tab"
                        :class="[tab == 2 ?'isActive':'']"
                        @click="tab = 2"
                      >
                        <a href="#description">
                          {{
                          $t("nbds_lang.gallery")
                          }}
                        </a>
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section
        class="horizontal-stripe light-background occurrenceKey__highlights seperator"
        v-if="tab ==1"
      >
        <div class="container--normal">
          <div class="balanced-row" style="justify-content: space-between">
            <div>
              <dl class="inline">
                <div>
                  <dt>{{$t('nbds_lang.submenu_species')}}</dt>
                  <dd>
                    <a style="cursor: pointer;">{{data.scientific_name}}</a>
                  </dd>
                </div>
              </dl>
            </div>
            <div>
              <dl class="inline">
                <div v-if="data.data_resource.organization_id">
                  <dt>{{$t('nbds_lang.submenu_publisher')}}</dt>
                  <dd>
                    <a
                      :href="'/detail/publisher/' + data.data_resource.organization_id"
                      style="cursor: pointer;"
                    >{{data.data_resource.organization.name}}</a>
                  </dd>
                </div>
                <div v-if="data.data_resource.id">
                  <dt>{{$t('nbds_lang.submenu_dataset')}}</dt>
                  <dd>
                    <a
                      :href="'/detail/dataset/' + data.data_resource.id"
                      style="cursor: pointer;"
                    >{{data.data_resource.title}}</a>
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </section>
      <section class="horizontal-stripe white-background seperator" v-if="tab ==1">
        <div class="container--normal">
          <DataTable :data="data"></DataTable>
        </div>
      </section>
      <section class="horizontal-stripe white-background seperator" v-if="tab ==2 && images > 0">
        <div class="container--normal">
          <Gallery :id="data.id"></Gallery>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import HeaderTaxon from "./header-detail.vue";
import Gallery from "./gallery";
import DataTable from "./dataTable";
export default {
  components: {
    HeaderTaxon,
    Gallery,
    DataTable,
  },
  props: ["data", "images"],
  data: function () {
    return {
      item: null,
      tab: 1,
      popUpSearch: -1,
    };
  },
  created() {
    if (this.data) {
      this.item = this.data;
    }
  },
  methods: {},
};
</script>
