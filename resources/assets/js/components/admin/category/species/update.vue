<template>
  <div class="white-box clearfix" style="min-height: 100vh">
    <components-notifications
      :typeInput="typeNotification"
      :textInput="textNotification"
    ></components-notifications>
    <div class="loading-roles" v-if="loading">
      <components-loading-page></components-loading-page>
    </div>
    <div class="col-md-6">
      <div class="form-material">
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
        >
          <label class="typo__label">
            Rank
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <multiselect
            v-model="rank"
            :options="listRank"
            :searchable="true"
            track-by="rank"
            label="rank"
            :show-labels="false"
            placeholder="Mời bạn nhập rank"
            v-validate="'required'"
            :disabled="true"
            name="rank"
          ></multiselect>
          <span
            v-show="errors.has('rank')"
            class="help is-danger"
            style="color: red"
          >
            Trường rank là trường bắt buộc
          </span>
        </div>
        <div
          class="col-md-12 no-padding"
          style="padding: 15px; position: relative; margin-right: 30px"
          v-show="rank ? rank.level_rank > 1 : false"
        >
          <label class="typo__label">
            Kingdom
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <multiselect
            v-model="kingdom"
            :options="listKingdom"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập kingdom"
            :disabled="rank ? (rank.level_rank == 2 ? false : true) : true"
            name="kingdom"
            @input="selectKingdom()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('kingdom')"
            class="help is-danger"
            style="color: red"
          >
            Trường kingdom là trường bắt buộc
          </span>
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
          <label class="typo__label">
            Phylum
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <multiselect
            v-model="phylum"
            :options="listPhylum"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập phylum"
            :disabled="rank ? (rank.level_rank == 3 ? false : true) : true"
            name="phylum"
            @input="selectPhylum()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('phylum')"
            class="help is-danger"
            style="color: red"
          >
            Trường phylum là trường bắt buộc
          </span>
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
          <label class="typo__label">
            Class
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <multiselect
            v-model="classes"
            :options="listClass"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập class"
            :disabled="rank ? (rank.level_rank == 4 ? false : true) : true"
            name="classes"
            @input="selectClass()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('classes')"
            class="help is-danger"
            style="color: red"
          >
            Trường classes là trường bắt buộc
          </span>
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
          <label class="typo__label">
            Order
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <multiselect
            v-model="order"
            :options="listOrder"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập order"
            :disabled="rank ? (rank.level_rank == 5 ? false : true) : true"
            name="order"
            @input="selectOrder()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('order')"
            class="help is-danger"
            style="color: red"
          >
            Trường order là trường bắt buộc
          </span>
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
          <label class="typo__label">
            Family
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <multiselect
            v-model="family"
            :options="listFamily"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập family"
            :disabled="rank ? (rank.level_rank == 6 ? false : true) : true"
            name="family"
            @input="selectFamily()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('family')"
            class="help is-danger"
            style="color: red"
          >
            Trường family là trường bắt buộc
          </span>
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
          <label class="typo__label">
            Genus
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <multiselect
            v-model="genus"
            :options="listGenus"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập genus"
            :disabled="rank ? (rank.level_rank == 7 ? false : true) : true"
            name="genus"
            @input="selectGenus()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('genus')"
            class="help is-danger"
            style="color: red"
          >
            Trường genus là trường bắt buộc
          </span>
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
          <label class="typo__label">
            Species
            <span style="color: red; margin-left: 5px">*</span>
          </label>
          <multiselect
            v-model="species"
            :options="listSpecies"
            :searchable="true"
            track-by="id"
            label="name"
            :show-labels="false"
            placeholder="Mời bạn nhập species"
            :disabled="rank ? (rank.level_rank == 8 ? false : true) : true"
            name="species"
            @input="selectSpecies()"
            v-validate="'required'"
          ></multiselect>
          <span
            v-show="errors.has('species')"
            class="help is-danger"
            style="color: red"
          >
            Trường species là trường bắt buộc
          </span>
          <div
            style="position: absolute; top: 10px; right: 0"
            v-if="loadingSpecies"
          >
            <components-loading-mini></components-loading-mini>
          </div>
        </div>
        <div class="form-group col-md-12 no-padding">
          <label for="example-code" class="col-md-12">
            Tên khoa học
            <span style="color: red; margin-left: 5px">*</span>
          </label>
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
            >
              Trường tên khoa học là trường bắt buộc
            </span>
          </div>
        </div>
        <div class="col-md-12 no-padding">
          <label class="col-md-12"> Tên tiếng việt </label>

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
              ></span
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
            placeholder="Mời bạn nhập trạng thái"
            v-validate="'required'"
            name="status"
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
            <button
              class="btn btn-success"
              @click="updateSpecies()"
              type="button"
            >
              {{ $t("admin.label.update") }}
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
    value: {},
    data: {},
  },
  data: function () {
    return {
      getDataFirst: true,
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
      rank: null,
      name: null,
      status: { id: 1, name: "Synonym" },
      name_vietnamese: null,
      synonym: null,
      listSynonym: [],
      loadingSynonyms: false,
      listStatus: [
        { id: 0, name: "Accepted" },
        { id: 1, name: "Synonym" },
      ],
      id: null,
      id_father: null,
      loading: true,
      typeNotification: null,
      textNotification: null,
      action: true,
      resource: [],
      listResource: [],
    };
  },
  created() {
    this.getListRank();
    this.getListKingdom();
    this.getListResource();
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
        })
        .finally(() => {
          this.loadingResource = false;
        });
    },
    updateSpecies() {
      this.$validator.validateAll();
      if (
        this.action == true &&
        ((this.status.id == 1 && this.rank && this.synonym) ||
          this.status.id == 0)
      ) {
        let father_id = null;
        if (this.rank.rank == "phylum") father_id = this.kingdom.id;
        else if (this.rank.rank == "class") father_id = this.phylum.id;
        else if (this.rank.rank == "order") father_id = this.classes.id;
        else if (this.rank.rank == "family") father_id = this.order.id;
        else if (this.rank.rank == "genus") father_id = this.family.id;
        else if (this.rank.rank == "species") father_id = this.genus.id;
        else if (this.rank.rank == "sub_species") father_id = this.species.id;
        else father_id = null;
        this.loading = true;
        this.action = false;
        axios
          .post(
            endpointhttp +
              "admin/species/update/" +
              this.rank.rank +
              "/" +
              this.id,
            {
              name: this.name,
              name_vietnamese: this.name_vietnamese,
              rank: this.rank ? this.rank.rank : "",
              father_id: father_id,
              id: this.id ? this.id : "",
              status: this.status.name,
              resource_id: this.resource ? this.resource.id : "",
              synonym_id:
                this.status.id == 1 && this.rank ? this.synonym.id : "",
            }
          )
          .then((response) => {
            if (response.data.message == "message.success") {
              this.action = true;
              this.typeNotification = 2;
              this.textNotification = "Cập nhật thành công.";
              window.location.href = "/admin/species";
            } else {
              this.action = true;
              this.typeNotification = 1;
              this.textNotification = "Cập nhật không thành công.";
            }
          })
          .catch((error) => {
            this.action = true;
            this.typeNotification = 1;
            this.textNotification = "Cập nhật không thành công.";
          })
          .finally(() => {
            this.loading = false;
            this.typeNotification = null;
            this.textNotification = null;
          });
      }
    },
    getData() {
      this.getDataFirst = false;
      if (this.value) {
        let data = JSON.parse(this.value);
        this.name = data.name;
        this.name_vietnamese = data.name_vietnamese;
        this.id = data.id;
        this.getListSynonyms(this.data, this.id);
        if (data.status == "accepted") {
          this.status = { id: 0, name: "Accepted" };
        }
        if (data.status == "synonym") {
          this.status = { id: 1, name: "Synonym" };
        }
        this.resource = data.resource;
        this.synonym = data.synonym ? data.synonym : null;
      }
      if (this.data) {
        for (let i in this.listRank) {
          if (this.listRank[i].rank == this.data) {
            this.rank = this.listRank[i];
          }
        }
      }
      let name_rank = this.rank.rank;

      if (name_rank == "phylum") {
        this.id_father = JSON.parse(this.value).kingdom_id;
        for (let i in this.listKingdom) {
          if (this.listKingdom[i].id == this.id_father) {
            this.kingdom = this.listKingdom[i];
          }
        }
        this.selectKingdom();
        this.loading = false;
      } else if (name_rank == "class") {
        this.id_father = JSON.parse(this.value).phylum_id;
        for (let i in this.listPhylum) {
          if (this.listPhylum[i].id == this.id_father) {
            this.phylum = this.listPhylum[i];
          }
        }
        this.selectPhylum();
        this.loading = false;
      } else if (name_rank == "order") {
        this.id_father = JSON.parse(this.value).class_id;
        for (let i in this.listClass) {
          if (this.listClass[i].id == this.id_father) {
            this.classes = this.listClass[i];
          }
        }
        this.selectClass();
        this.loading = false;
      } else if (name_rank == "family") {
        this.id_father = JSON.parse(this.value).order_id;
        for (let i in this.listOrder) {
          if (this.listOrder[i].id == this.id_father) {
            this.order = this.listOrder[i];
          }
        }
        this.selectOrder();
        this.loading = false;
      } else if (name_rank == "genus") {
        this.id_father = JSON.parse(this.value).family_id;
        for (let i in this.listFamily) {
          if (this.listFamily[i].id == this.id_father) {
            this.family = this.listFamily[i];
          }
        }
        this.selectFamily();
        this.loading = false;
      } else if (name_rank == "species") {
        this.id_father = JSON.parse(this.value).genus_id;
        for (let i in this.listGenus) {
          if (this.listGenus[i].id == this.id_father) {
            this.genus = this.listGenus[i];
          }
        }
        this.selectGenus();
        this.loading = false;
      } else if (name_rank == "sub_species") {
        this.id_father = JSON.parse(this.value).species_id;
        for (let i in this.listSpecies) {
          if (this.listSpecies[i].id == this.id_father) {
            this.species = this.listSpecies[i];
          }
        }
        this.selectSpecies();
        this.loading = false;
      } else {
        this.loading = false;
      }
    },
    getListRank() {
      axios.get(env.endpointhttp + "admin/ranks/info").then((response) => {
        this.listRank = response.data.result;
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
        .finally(() => {
          if (this.getDataFirst) {
            this.getData();
          }
        });
    },
    getListSynonyms(rank, id) {
      this.loadingSynonyms = true;
      axios
        .get(
          env.endpointhttp + "admin/synonyms/info?rank=" + rank + "&id=" + id
        )
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
