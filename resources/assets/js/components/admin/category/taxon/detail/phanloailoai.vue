<template>
  <div class="">
    <div class="row">
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">
          {{ $t("nbds_lang.darwin_core_taxon.information.kingdom") }}
        </label>
        <div>
          <multiselect
            v-model="form.kingdom"
            :options="kingdom.items"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            @input="onChangeKingDom()"
            label="name"
            name="kingdom"
            :placeholder="`Mời bạn chọn ${$t(
              'nbds_lang.darwin_core_taxon.information.kingdom'
            )}`"
            :loading="kingdom.loading"
          >
          </multiselect>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">
          {{ $t("nbds_lang.darwin_core_taxon.information.phylums") }}
        </label>
        <div>
          <multiselect
            v-model="form.phylum"
            :options="phylumFilters"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            @input="onChangePhylum()"
            label="name"
            name="phylum"
            :placeholder="`Mời bạn chọn ${$t(
              'nbds_lang.darwin_core_taxon.information.phylums'
            )}`"
            :loading="phylum.loading"
          >
          </multiselect>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">
          {{ $t("nbds_lang.darwin_core_taxon.information.class") }}
        </label>
        <div>
          <multiselect
            v-model="form.classes"
            :options="classesFilters"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            @input="onChangeClass()"
            label="name"
            name="classes"
            :placeholder="`Mời bạn chọn ${$t(
              'nbds_lang.darwin_core_taxon.information.class'
            )}`"
            :loading="classes.loading"
          >
          </multiselect>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">
          {{ $t("nbds_lang.darwin_core_taxon.information.order") }}
        </label>
        <div>
          <multiselect
            v-model="form.order"
            :options="orderFilters"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            @input="onChangeOrder()"
            label="name"
            :placeholder="`Mời bạn chọn ${$t(
              'nbds_lang.darwin_core_taxon.information.order'
            )}`"
            name="order"
            :loading="order.loading"
          >
          </multiselect>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">
          {{ $t("nbds_lang.darwin_core_taxon.information.family") }}
        </label>
        <div>
          <multiselect
            v-model="form.family"
            :options="familyFilters"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            @input="onChangeFamily()"
            :placeholder="`Mời bạn chọn ${$t(
              'nbds_lang.darwin_core_taxon.information.family'
            )}`"
            label="name"
            name="family"
            :loading="family.loading"
          >
          </multiselect>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">
          {{ $t("nbds_lang.darwin_core_taxon.information.genus") }}
        </label>
        <div>
          <multiselect
            v-model="form.genus"
            :options="genusFilters"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            @input="onChangeGenus()"
            :placeholder="`Mời bạn chọn ${$t(
              'nbds_lang.darwin_core_taxon.information.genus'
            )}`"
            label="name"
            name="genus"
            :loading="genus.loading"
          >
          </multiselect>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">
          {{ $t("nbds_lang.darwin_core_taxon.information.species") }}
        </label>
        <div>
          <multiselect
            v-model="form.species"
            :options="speciesFilters"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            @input="onChangeSpecies()"
            placeholder="Mời bạn chọn species"
            label="name"
            name="species"
            :loading="species.loading"
          >
          </multiselect>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label">
          {{ $t("nbds_lang.darwin_core_taxon.information.sub_species_id") }}
        </label>
        <div>
          <multiselect
            v-model="form.subspecies"
            :options="subspeciesFilters"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            @input="onChangeSubSpecies()"
            :placeholder="`Mời bạn chọn ${$t(
              'nbds_lang.darwin_core_taxon.information.sub_species_id'
            )}`"
            label="name"
            name="subspecies"
            :loading="subspecies.loading"
          >
          </multiselect>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Cấp bậc cao hơn </label>
        <input v-model="model.higher_classification" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Specific Epithet </label>
        <input v-model="model.specific_epithet" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Infraspecific Epithet </label>
        <input v-model="model.infraspecific_epithet" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Cấp bậc phân loại </label>
        <input v-model="model.taxon_rank" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Bậc phân loại nguyên văn </label>
        <input v-model="model.verbatim_taxon_rank" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tên tác giả khoa học </label>
        <input
          v-model="model.scientific_name_authorship"
          class="form-control"
        />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tên bản ngữ </label>
        <input v-model="model.vernacular_name" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tên bản ngữ tiếng anh </label>
        <input v-model="model.vernacular_name_english" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tên bản ngữ khác </label>
        <input v-model="model.vernacular_name_others" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Mã danh pháp </label>
        <input v-model="model.nomenclatural_code" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tình trạng loài </label>
        <input v-model="model.taxonomic_status" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tình trạng danh pháp </label>
        <input v-model="model.nomenclatural_status" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Nhận xét </label>
        <input v-model="model.taxon_remarks" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Sở hữu </label>
        <input v-model="model.rights" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Chủ sở hữu </label>
        <input v-model="model.rights_holder" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Quyền truy cập </label>
        <input v-model="model.access_rights" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Trích dẫn </label>
        <input v-model="model.bibliographic_citation" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Tham chiếu </label>
        <input v-model="model.references" class="form-control" />
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Bộ dữ liệu </label>
        <div>
          <multiselect
            v-model="form.dataset_resource"
            :options="dataset_resource.items"
            :searchable="true"
            track-by="id"
            :show-labels="false"
            label="title"
            name="dataset_resource"
            :placeholder="`Mời bạn chọn bộ dữ liệu`"
            :loading="dataset_resource.loading"
            @input="onChangeDatasetResource()"
            @search-change="onDatasetResourceSearchChange"
          >
          </multiselect>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 form-group">
        <label class="form-label"> Chuẩn phân loại </label>
        <div>
          <DynamicSelect
            v-model="model.resource_id"
            :items="danhmucs.resources"
            item-value="id"
            item-text="name"
            :placeholder="`Mời bạn chọn chuẩn phân loại`"
          >
          </DynamicSelect>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DynamicSelect from "@/modules/dynamic-form/fields/dynamic-select.vue";
import Multiselect from "vue-multiselect";
import {
  kingdom_url_get,
  rank_url_get,
  phylum_url_get,
  class_url_get,
  order_url_get,
  family_url_get,
  genus_url_get,
  species_url_get,
  subspecies_url_get,
  dataset_resource_url_get,
} from "@/routes.js";
import { debounce } from "lodash";
export default {
  components: { Multiselect, DynamicSelect },
  props: {
    value: { type: Object, default: () => ({}) },
    danhmucs: { type: Object, default: () => ({}) },
  },
  computed: {
    model: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
    phylumFilters() {
      if (!this.form.kingdom) return this.phylum.items;
      return this.phylum.items.filter(
        (x) => x.kingdom_id == this.form.kingdom.id
      );
    },
    classesFilters() {
      if (!this.form.phylum) return this.classes.items;
      return this.classes.items.filter(
        (x) => x.phylum_id == this.form.phylum.id
      );
    },
    orderFilters() {
      if (!this.form.classes) return this.order.items;
      return this.order.items.filter((x) => x.class_id == this.form.classes.id);
    },
    familyFilters() {
      if (!this.form.order) return this.family.items;
      return this.family.items.filter((x) => x.order_id == this.form.order.id);
    },
    genusFilters() {
      if (!this.form.family) return this.genus.items;
      return this.genus.items.filter((x) => x.family_id == this.form.family.id);
    },
    speciesFilters() {
      if (!this.form.genus) return this.species.items;
      return this.species.items.filter((x) => x.genus_id == this.form.genus.id);
    },
    subspeciesFilters() {
      if (!this.form.species) return this.subspecies.items;
      return this.subspecies.items.filter(
        (x) => x.species_id == this.form.species.id
      );
    },
  },
  data: () => ({
    form: {
      kingdom: null,
      phylum: null,
      classes: null,
      order: null,
      family: null,
      genus: null,
      species: null,
      subspecies: null,
      dataset_resource: null,
    },
    rank: { loading: false, items: [] },
    kingdom: { loading: false, items: [] },
    phylum: { loading: false, items: [] },
    classes: { loading: false, items: [] },
    order: { loading: false, items: [] },
    family: { loading: false, items: [] },
    genus: { loading: false, items: [] },
    species: { loading: false, items: [] },
    subspecies: { loading: false, items: [] },
    dataset_resource: { loading: false, items: [], search: "" },
  }),
  created() {
    this.getAllData();
    [
      "dataset_resource",
      "kingdom",
      "phylum",
      "classes",
      "order",
      "family",
      "genus",
      "species",
      "subspecies",
    ].forEach((key) => {
      this.form[key] = this.value[key];
    });
    this.form.classes = this.value.class;
  },
  methods: {
    onDatasetResourceSearchChange: debounce(function (e) {
      this.getListDataset({ search: e });
    }, 200),
    getAllData() {
      this.getListKingdom();
      this.getListPhylum();
      this.getListClass();
      this.getListOrder();
      this.getListFamily();
      this.getListGenus();
      this.getListSpecies();
      this.getListSubSpecies();
      this.getListDataset();
    },
    getListDataset(params = {}) {
      this.dataset_resource.loading = true;
      axios
        .get(dataset_resource_url_get, { params })
        .then((res) => {
          this.dataset_resource.items = res.data.result.data;
        })
        .finally(() => {
          this.dataset_resource.loading = false;
        });
    },
    getListKingdom() {
      this.kingdom.loading = true;
      axios
        .get(kingdom_url_get)
        .then((res) => {
          this.kingdom.items = res.data.kingdom;
        })
        .finally(() => {
          this.kingdom.loading = false;
        });
    },
    getListPhylum() {
      this.phylum.loading = true;
      axios
        .get(phylum_url_get)
        .then((res) => {
          this.phylum.items = res.data.Phylums;
        })
        .finally(() => {
          this.phylum.loading = false;
        });
    },
    getListClass() {
      this.classes.loading = true;
      axios
        .get(class_url_get)
        .then((res) => {
          this.classes.items = res.data.class;
        })
        .finally(() => {
          this.classes.loading = false;
        });
    },
    getListOrder() {
      this.order.loading = true;
      axios
        .get(order_url_get)
        .then((response) => {
          this.order.items = response.data.order;
        })
        .finally(() => {
          this.order.loading = false;
        });
    },
    getListFamily() {
      this.family.loading = true;
      axios
        .get(family_url_get)
        .then((response) => {
          this.family.items = response.data.family;
        })
        .finally(() => {
          this.family.loading = false;
        });
    },
    getListGenus() {
      this.genus.loading = true;
      axios
        .get(genus_url_get)
        .then((response) => {
          this.genus.items = response.data.genus;
        })
        .finally(() => (this.genus.loading = false));
    },
    getListSpecies() {
      this.species.loading = true;
      axios
        .get(species_url_get)
        .then((response) => {
          this.species.items = response.data.species;
        })
        .finally(() => (this.species.loading = false));
    },
    getListSubSpecies() {
      this.subspecies.loading = true;
      axios
        .get(subspecies_url_get)
        .then((response) => {
          this.subspecies.items = response.data.subspecies;
        })
        .finally(() => (this.subspecies.loading = false));
    },
    onChangeKingDom() {
      this.form.phylum = null;
      this.form.classes = null;
      this.form.order = null;
      this.form.family = null;
      this.form.genus = null;
      this.form.species = null;
      this.form.subspecies = null;
      this.onUpdateTaxon();
    },
    onChangePhylum() {
      this.form.classes = null;
      this.form.order = null;
      this.form.family = null;
      this.form.genus = null;
      this.form.species = null;
      this.form.subspecies = null;
      this.onUpdateTaxon();
    },
    onChangeClass() {
      this.form.order = null;
      this.form.family = null;
      this.form.genus = null;
      this.form.species = null;
      this.form.subspecies = null;
      this.onUpdateTaxon();
    },
    onChangeOrder() {
      this.form.family = null;
      this.form.genus = null;
      this.form.species = null;
      this.form.subspecies = null;
      this.onUpdateTaxon();
    },
    onChangeFamily() {
      this.form.genus = null;
      this.form.species = null;
      this.form.subspecies = null;
      this.onUpdateTaxon();
    },
    onChangeGenus() {
      this.form.species = null;
      this.form.subspecies = null;
      this.onUpdateTaxon();
    },
    onChangeSpecies() {
      this.form.subspecies = null;
      this.onUpdateTaxon();
    },
    onChangeSubSpecies() {
      this.onUpdateTaxon();
    },
    onUpdateTaxon() {
      if (this.form.subspecies) {
        this.form.species = this.species.items.find(
          (x) => x.id == this.form.subspecies.species_id
        );
      }
      if (this.form.species) {
        this.form.genus = this.genus.items.find(
          (x) => x.id == this.form.species.genus_id
        );
      }
      if (this.form.genus) {
        this.form.family = this.family.items.find(
          (x) => x.id == this.form.genus.family_id
        );
      }
      if (this.form.family) {
        this.form.order = this.order.items.find(
          (x) => x.id == this.form.family.order_id
        );
      }
      if (this.form.order) {
        this.form.classes = this.classes.items.find(
          (x) => x.id == this.form.order.class_id
        );
      }
      if (this.form.classes) {
        this.form.phylum = this.phylum.items.find(
          (x) => x.id == this.form.classes.phylum_id
        );
      }
      if (this.form.phylum) {
        this.form.kingdom = this.kingdom.items.find(
          (x) => x.id == this.form.phylum.kingdom_id
        );
      }
      this.$emit(
        "input",
        Object.assign(this.model, {
          kingdom_id: this.getIdFromForm("kingdom"),
          phylum_id: this.getIdFromForm("phylum"),
          class_id: this.getIdFromForm("classes"),
          order_id: this.getIdFromForm("order"),
          family_id: this.getIdFromForm("family"),
          genus_id: this.getIdFromForm("genus"),
          species_id: this.getIdFromForm("species"),
          sub_species_id: this.getIdFromForm("subspecies"),
        })
      );
    },
    onChangeDatasetResource() {
      this.$emit(
        "input",
        Object.assign(this.model, {
          dataset_resource_id: this.getIdFromForm("dataset_resource"),
        })
      );
    },
    getIdFromForm(type) {
      if (!this.form[type]) return null;
      return this.form[type].id;
    },
    validateAll() {
      return this.$validator.validateAll();
    },
  },
};
</script>

<style></style>
