<template>
  <div class="white-box clearfix" style="min-height: 100vh">
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    >
    </components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div class="col-md-6">
      <div class="form-material">
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
        >
          <label class="typo__label"
            >{{ $t("nbds_lang.rank")
            }}<span style="color: red; margin-left: 5px">*</span></label
          >
          <multiselect
            v-model="rank"
            :options="listRank"
            :searchable="true"
            track-by="rank"
            label="rank"
            :show-labels="false"
            @input="selectStatus()"
            placeholder="Mời bạn nhập rank"
            v-validate="'required'"
            name="rank"
          >
          </multiselect>
          <span
            v-show="errors.has('rank')"
            class="help is-danger"
            style="color: red"
            >Trường rank là trường bắt buộc</span
          >
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
          v-show="rank ? rank.level_rank > 1 : false"
        >
          <label class="typo__label"
            >Kingdom<span style="color: red; margin-left: 5px">*</span></label
          >
          <multiselect
            v-model="kingdom"
            :options="listKingdom"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập kingdom"
            :disabled="loadingKingDom"
            name="kingdom"
            @input="selectKingdom()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('kingdom')"
            class="help is-danger"
            style="color: red"
            >Trường kingdom là trường bắt buộc</span
          >
          <div
            style="position: absolute; top: 10px; right: 0"
            v-if="loadingKingDom"
          >
            <components-loading-mini></components-loading-mini>
          </div>
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
          v-show="rank ? rank.level_rank > 2 : false"
        >
          <label class="typo__label"
            >Phylum<span style="color: red; margin-left: 5px">*</span></label
          >
          <multiselect
            v-model="phylum"
            :options="listPhylum"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập phylum"
            :disabled="loadingPhylum"
            name="phylum"
            @input="selectPhylum()"
            v-validate="'required'"
          >
          </multiselect>
          <span
            v-show="errors.has('phylum')"
            class="help is-danger"
            style="color: red"
            >Trường phylum là trường bắt buộc</span
          >
          <div
            style="position: absolute; top: 10px; right: 0"
            v-if="loadingPhylum"
          >
            <components-loading-mini></components-loading-mini>
          </div>
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
          v-show="rank ? rank.level_rank > 3 : false"
        >
          <label class="typo__label"
            >Class<span style="color: red; margin-left: 5px">*</span></label
          >
          <multiselect
            v-model="classes"
            :options="listClass"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập class"
            :disabled="loadingClass"
            name="classes"
            @input="selectClass()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('classes')"
            class="help is-danger"
            style="color: red"
            >Trường classes là trường bắt buộc</span
          >
          <div
            style="position: absolute; top: 10px; right: 0"
            v-if="loadingClass"
          >
            <components-loading-mini></components-loading-mini>
          </div>
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
          v-show="rank ? rank.level_rank > 4 : false"
        >
          <label class="typo__label"
            >Order<span style="color: red; margin-left: 5px">*</span></label
          >
          <multiselect
            v-model="order"
            :options="listOrder"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập order"
            :disabled="loadingOrder"
            name="order"
            @input="selectOrder()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('order')"
            class="help is-danger"
            style="color: red"
            >Trường order là trường bắt buộc</span
          >
          <div
            style="position: absolute; top: 10px; right: 0"
            v-if="loadingOrder"
          >
            <components-loading-mini></components-loading-mini>
          </div>
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
          v-show="rank ? rank.level_rank > 5 : false"
        >
          <label class="typo__label"
            >Family<span style="color: red; margin-left: 5px">*</span></label
          >
          <multiselect
            v-model="family"
            :options="listFamily"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập family"
            :disabled="loadingFamily"
            name="family"
            @input="selectFamily()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('family')"
            class="help is-danger"
            style="color: red"
            >Trường family là trường bắt buộc</span
          >
          <div
            style="position: absolute; top: 10px; right: 0"
            v-if="loadingFamily"
          >
            <components-loading-mini></components-loading-mini>
          </div>
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
          v-show="rank ? rank.level_rank > 6 : false"
        >
          <label class="typo__label"
            >Genus<span style="color: red; margin-left: 5px">*</span></label
          >
          <multiselect
            v-model="genus"
            :options="listGenus"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập genus"
            :disabled="loadingGenus"
            name="genus"
            @input="selectGenus()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('genus')"
            class="help is-danger"
            style="color: red"
            >Trường genus là trường bắt buộc</span
          >
          <div
            style="position: absolute; top: 10px; right: 0"
            v-if="loadingGenus"
          >
            <components-loading-mini></components-loading-mini>
          </div>
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
          v-show="rank ? rank.level_rank > 7 : false"
        >
          <label class="typo__label"
            >Species<span style="color: red; margin-left: 5px">*</span></label
          >
          <multiselect
            v-model="species"
            :options="listSpecies"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập species"
            :disabled="loadingSpecies"
            name="species"
            @input="selectSpecies()"
            v-validate="'required'"
          >
          </multiselect>
          <span
            v-show="errors.has('species')"
            class="help is-danger"
            style="color: red"
            >Trường species là trường bắt buộc</span
          >
          <div
            style="position: absolute; top: 10px; right: 0"
            v-if="loadingSpecies"
          >
            <components-loading-mini></components-loading-mini>
          </div>
        </div>
        <div class="form-group col-md-12 no-padding">
          <label for="example-code" class="col-md-12"
            >Tên khoa học<span style="color: red; margin-left: 5px"
              >*</span
            ></label
          >
          <div class="col-md-12">
            <input
              type="text"
              v-model="name"
              class="form-control form-control-line"
              name="name"
              placeholder="Mời bạn nhập tên khoa học"
              id="example-code"
              v-validate="'required'"
            />
            <span
              v-show="errors.has('name')"
              class="help is-danger"
              style="color: red"
              >Trường tên khoa học là trường bắt buộc</span
            >
          </div>
        </div>
        <div class="col-md-12 no-padding">
          <label class="col-md-12">Tên tiếng việt</label>
          <div class="col-md-12">
            <input
              type="text"
              v-model="name_vietnamese"
              class="form-control form-control-line"
              name="name_vietnamese"
              placeholder="Mời bạn nhập tên tiếng việt"
            />
          </div>
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
        >
          <label class="typo__label">
            Loại
            <span
              v-if="rank.rank == 'species'"
              style="color: red; margin-left: 5px"
              >*</span
            ></label
          >
          <multiselect
            v-if="rank.rank == 'species'"
            v-model="resource"
            :options="listResource"
            track-by="id"
            label="name"
            :disabled="loadingResource"
            :show-labels="false"
            placeholder="Mời bạn nhập loại"
            name="resource"
            :allow-empty="false"
          >
          </multiselect>
          <span
            v-if="rank.rank == 'species'"
            v-show="errors.has('resource')"
            class="help is-danger"
            style="color: red"
            >Trường loại là trường bắt buộc</span
          >
          <multiselect
            v-if="rank.rank != 'species'"
            v-model="resource"
            :options="listResource"
            track-by="id"
            label="name"
            :disabled="loadingResource"
            :show-labels="false"
            placeholder="Mời bạn nhập loại"
            :clear-on-select="true"
            name="resource"
            :allow-empty="true"
          >
          </multiselect>
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
        >
          <label class="typo__label"
            >Trạng thái<span style="color: red; margin-left: 5px"
              >*</span
            ></label
          >
          <multiselect
            v-model="status"
            :options="listStatus"
            track-by="id"
            label="name"
            :show-labels="false"
            @input="selectStatus()"
            placeholder="Mời bạn nhập trạng thái"
            v-validate="'required'"
            name="status"
            :allow-empty="false"
          >
          </multiselect>
          <span
            v-show="errors.has('status')"
            class="help is-danger"
            style="color: red"
            >Trường trạng thái là trường bắt buộc</span
          >
        </div>
        <div
          v-show="status.id == 1 && rank"
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
        >
          <label class="typo__label"
            >Đồng danh<span style="color: red; margin-left: 5px">*</span></label
          >
          <multiselect
            v-model="synonym"
            :options="listSynonym"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập đồng danh"
            v-validate="'required'"
            name="synonym"
          >
          </multiselect>
          <span
            v-show="errors.has('synonym')"
            class="help is-danger"
            style="color: red"
            >Trường đồng danh là trường bắt buộc</span
          >
          <div
            style="position: absolute; top: 10px; right: 0"
            v-if="loadingSynonyms"
          >
            <components-loading-mini></components-loading-mini>
          </div>
        </div>
        <div class="form-group col-md-12 no-padding">
          <div class="col-sm-12 text-right">
            <button
              class="btn btn-inverse m-r-20"
              type="button"
              @click="cancelAdd()"
            >
              {{ $t("admin.label.cancel") }}
            </button>
            <button class="btn btn-success" @click="addSpecies()" type="button">
              {{ $t("admin.label.create_new") }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import * as env from "../../../../env.js";
import { endpoint, endpointhttp } from "../../../../env.js";
import Multiselect from "vue-multiselect";
import { ValidationProvider } from "vee-validate";
import * as routes from "../../../../routes.js";
export default {
  components: {
    Multiselect,
    ValidationProvider,
  },
  props: {
    value: { type: String },
  },
  data: function () {
    return {
      listRank: [],
      kingdom: null,
      listKingdom: [],
      loadingKingDom: false,
      phylum: null,
      listPhylum: [],
      loadingPhylum: false,
      classes: null,
      listClass: [],
      loadingClass: false,
      order: null,
      listOrder: [],
      loadingOrder: false,
      family: null,
      listFamily: [],
      loadingFamily: false,
      genus: null,
      listGenus: [],
      loadingGenus: false,
      species: null,
      listSpecies: [],
      loadingSpecies: false,
      listSynonym: [],
      loadingSynonyms: false,
      rank: null,
      name: null,
      name_vietnamese: null,
      synonym: null,
      status: { id: 0, name: "Accepted" },
      loading: false,
      typeNotification: null,
      textNotification: null,
      action: true,
      listStatus: [
        { id: 0, name: "Accepted" },
        { id: 1, name: "Synonym" },
      ],
      resource: [],
      listResource: [],
    };
  },
  created() {
    this.getListRank();
    this.getListResource();
    this.getListKingdom();
  },
  methods: {
    cancelAdd() {
      window.location.href = "/admin/species";
    },
    getListResource() {
      this.loadingResource = true;
      axios
        .get(env.endpointhttp + routes.getresource_type)
        .then((response) => {
          this.listResource = response.data.result;
          this.resource =
            this.listResource.find((x) => x.id == 1) || this.listResource[0];
        })
        .finally(() => {
          this.loadingResource = false;
        });
    },
    addSpecies() {
      this.$validator.validateAll();
      if (
        this.action == true &&
        ((this.status.id == 1 && this.rank && this.synonym) ||
          this.status.id == 0)
      ) {
        this.loading = true;
        this.action = false;
        axios
          .post(endpointhttp + "admin/species/add", {
            name: this.name,
            name_vietnamese: this.name_vietnamese,
            rank: this.rank ? this.rank.rank : "",
            kingdom_id: this.kingdom ? this.kingdom.id : "",
            phylum_id: this.phylum ? this.phylum.id : "",
            class_id: this.classes ? this.classes.id : "",
            order_id: this.order ? this.order.id : "",
            family_id: this.family ? this.family.id : "",
            genus_id: this.genus ? this.genus.id : "",
            species_id: this.species ? this.species.id : "",
            status: this.status.name,
            synonym_id: this.status.id == 1 && this.rank ? this.synonym.id : "",
            resource_id: this.resource ? this.resource.id : "",
          })
          .then((response) => {
            if (response.data.message == "message.success") {
              this.action = true;
              this.typeNotification = 2;
              this.textNotification = "Thêm mới thành công.";
              window.location.href = "/admin/species";
            } else {
              this.action = true;
              this.typeNotification = 1;
              this.textNotification = "Thêm mới không thành công.";
            }
          })
          .catch((error) => {
            this.action = true;
            this.typeNotification = 1;
            this.textNotification = "Thêm mới không thành công.";
          })
          .finally(() => {
            this.loading = false;
            this.typeNotification = null;
            this.textNotification = null;
          });
      }
    },
    getListRank() {
      axios.get(env.endpointhttp + "admin/ranks/info").then((response) => {
        this.listRank = response.data.result;
        this.rank = this.listRank[0];
      });
    },
    selectKingdom() {
      this.phylum = null;
      this.classes = null;
      this.order = null;
      this.family = null;
      this.genus = null;
      this.species = null;
      this.getListPhylum();
    },
    selectPhylum() {
      this.classes = null;
      this.order = null;
      this.family = null;
      this.genus = null;
      this.species = null;
      this.getKingdom();
      this.getListClass();
    },
    selectClass() {
      this.order = null;
      this.family = null;
      this.genus = null;
      this.species = null;
      this.getPhylum();
      this.getListOrder();
    },
    selectOrder() {
      this.family = null;
      this.genus = null;
      this.species = null;
      this.getClass();
      this.getListFamily();
    },
    selectFamily() {
      this.genus = null;
      this.species = null;
      this.getOrder();
      this.getListGenus();
    },
    selectGenus() {
      this.species = null;
      this.getFamily();
      this.getListSpecies();
    },
    selectSpecies() {
      this.getGenus();
    },
    selectStatus() {
      if (this.status.id == 1 && this.rank) {
        this.getListSynonyms();
      }
    },
    getListKingdom() {
      this.loadingKingDom = true;
      axios
        .get(env.endpointhttp + "admin/kingdom/info")
        .then((response) => {
          this.listKingdom = response.data.kingdom;
          this.loadingKingDom = false;
        })
        .finally(() => this.getListPhylum());
    },
    getListPhylum() {
      this.loadingPhylum = true;
      axios
        .get(env.endpointhttp + "admin/phylum/info", {
          params: {
            kingdom_id: this.kingdom ? this.kingdom.id : "",
          },
        })
        .then((response) => {
          this.listPhylum = response.data.Phylums;
          this.loadingPhylum = false;
        })
        .finally(() => this.getListClass());
    },
    getListClass() {
      this.loadingClass = true;
      axios
        .get(env.endpointhttp + "admin/class/info", {
          params: {
            phylum_id: this.phylum ? this.phylum.id : "",
          },
        })
        .then((response) => {
          this.listClass = response.data.class;
          this.loadingClass = false;
        })
        .finally(() => this.getListOrder());
    },
    getListOrder() {
      this.loadingOrder = true;
      axios
        .get(env.endpointhttp + "admin/order/info", {
          params: {
            class_id: this.classes ? this.classes.id : "",
          },
        })
        .then((response) => {
          this.listOrder = response.data.order;
          this.loadingOrder = false;
        })
        .finally(() => this.getListFamily());
    },
    getListFamily() {
      this.loadingFamily = true;
      axios
        .get(env.endpointhttp + "admin/family/info", {
          params: {
            order_id: this.order ? this.order.id : "",
          },
        })
        .then((response) => {
          this.listFamily = response.data.family;
          this.loadingFamily = false;
        })
        .finally(() => this.getListGenus());
    },
    getListGenus() {
      this.loadingGenus = true;
      axios
        .get(env.endpointhttp + "admin/genus/info", {
          params: {
            family_id: this.family ? this.family.id : "",
          },
        })
        .then((response) => {
          this.listGenus = response.data.genus;
          this.loadingGenus = false;
        })
        .finally(() => this.getListSpecies());
    },
    getListSpecies() {
      this.loadingSpecies = true;
      axios
        .get(env.endpointhttp + "admin/species/info", {
          params: {
            genus_id: this.genus ? this.genus.id : "",
          },
        })
        .then((response) => {
          this.listSpecies = response.data.species;
          this.loadingSpecies = false;
        })
        .then();
    },
    getListSynonyms() {
      this.loadingSynonyms = true;
      axios
        .get(env.endpointhttp + "admin/synonyms/info", {
          params: {
            rank: this.rank ? this.rank.rank : "kingdom",
          },
        })
        .then((response) => {
          this.listSynonym = response.data.result;
          this.loadingSynonyms = false;
        });
    },
    getKingdom() {
      if (this.phylum) {
        this.loadingKingDom = true;
        axios
          .get(env.endpointhttp + "admin/kingdom/info", {
            params: {
              kingdom_id: this.phylum ? this.phylum.kingdom_id : "",
            },
          })
          .then((response) => {
            this.kingdom = response.data.kingdom[0];
            this.loadingKingDom = false;
            this.getListPhylum();
          });
      }
    },
    getPhylum() {
      if (this.classes) {
        this.loadingPhylum = true;
        axios
          .get(env.endpointhttp + "admin/phylum/info", {
            params: {
              phylum_id: this.classes ? this.classes.phylum_id : "",
            },
          })
          .then((response) => {
            this.phylum = response.data.Phylums[0];
            this.loadingPhylum = false;
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => this.getKingdom());
      }
    },
    getClass() {
      if (this.order) {
        this.loadingClass = true;
        axios
          .get(env.endpointhttp + "admin/class/info", {
            params: {
              class_id: this.order ? this.order.class_id : "",
            },
          })
          .then((response) => {
            this.classes = response.data.class[0];
            this.loadingClass = false;
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => this.getPhylum());
      }
    },
    getOrder() {
      if (this.family) {
        this.loadingOrder = true;
        axios
          .get(env.endpointhttp + "admin/order/info", {
            params: {
              order_id: this.family ? this.family.order_id : "",
            },
          })
          .then((response) => {
            this.order = response.data.order[0];
            this.loadingOrder = false;
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => this.getClass());
      }
    },
    getFamily() {
      if (this.genus) {
        this.loadingFamily = true;
        axios
          .get(env.endpointhttp + "admin/family/info", {
            params: {
              family_id: this.genus ? this.genus.family_id : "",
            },
          })
          .then((response) => {
            this.family = response.data.family[0];
            this.loadingFamily = false;
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => this.getOrder());
      }
    },
    getGenus() {
      if (this.species) {
        this.loadingGenus = true;
        axios
          .get(env.endpointhttp + "admin/genus/info", {
            params: {
              genus_id: this.species ? this.species.genus_id : "",
            },
          })
          .then((response) => {
            this.genus = response.data.genus[0];
            this.loadingGenus = false;
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => this.getFamily());
      }
    },
  },
};
</script>
